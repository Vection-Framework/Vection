<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Database;

use Vection\Component\Database\Exception\BackupSchemaException;
use Vection\Component\Database\Exception\UpdateSchemaException;
use Vection\Contracts\Database\DatabaseInterface;
use Vection\Contracts\Database\SchemaInterface;

/**
 * Class Database
 *
 * @package Vection\Component\Database
 */
class Database implements DatabaseInterface
{

    /** @var array */
    protected $connInfo;

    /** @var \PDO */
    protected $PDO;

    /** @var string[] */
    protected $SQLQueue;

    /** @var string[] */
    protected $SQLFails;

    /** @var string */
    protected $workDir;

    /**
     * Database constructor.
     *
     * @param string $host
     * @param int    $port
     * @param string $user
     * @param string $password
     * @param string $workDir
     */
    public function __construct(string $host, int $port, string $user, string $password, string $workDir)
    {
        $this->PDO      = new \PDO("mysql:host={$host};port={$port}", $user, $password);
        $this->connInfo = ['host' => $host, 'port' => $port, 'user' => $user, 'password' => $password];
        $this->SQLQueue = [];
        $this->SQLFails = [];

        $workDir = \rtrim($workDir, '/');

        if ( ! \file_exists($workDir) ) {
            if ( ! \mkdir($workDir, 0665, true) ) {
                throw new \RuntimeException("Given working directory does not exists.");
            }
        }

        ! \is_writeable($workDir) && \chmod($workDir, 0665);

        if ( ! \is_writeable($workDir) ) {
            throw new \RuntimeException("Given working directory is not writeable.");
        }

        $this->workDir = $workDir.'/vection/database/backup';

        if ( ! \file_exists($this->workDir) && ! \mkdir($this->workDir, 0665, true) ) {
            throw new \RuntimeException("Given working directory is not writeable.");
        }
    }

    # region Getter / Setter

    /**
     * @return string[]
     */
    public function getSQLQueue(): array
    {
        return $this->SQLQueue;
    }

    /**
     *
     * @return string[][]
     */
    public function getSQLFails(): array
    {
        return $this->SQLFails;
    }

    /**
     *
     * @return bool
     */
    public function hasSQLFails(): bool
    {
        return \count($this->SQLFails) > 0;
    }

    # endregion

    /**
     * @param string $name
     *
     * @return bool
     */
    public function schemaExists(string $name): bool
    {
        return $this->PDO->query("SHOW DATABASES LIKE '{$name}'")->rowCount() > 0;
    }

    /**
     * @param string $name
     */
    public function dropSchema(string $name): void
    {
        if ( $this->schemaExists($name) ) {
            $this->PDO->exec("DROP DATABASES IF EXISTS `{$name}`");
        }
    }

    /**
     * @param SchemaInterface $schema
     *
     * @return string
     *
     * @throws BackupSchemaException
     */
    public function createSchemaBackup(SchemaInterface $schema): string
    {
        # Check whether schema already exists
        if ( ! $this->schemaExists($schema->getName()) ) {
            throw new BackupSchemaException("Schema '{$schema->getName()}' does not exists.");
        }

        list($h, $po, $u, $pa) = \array_values($this->connInfo);
        $file = $this->workDir."/{$schema->getName()}.sql";

        $result = \exec(
            "mysqldump --host $h --port $po --user $u --password=\"$pa\" {$schema->getName()} > $file",
            $output,
            $status
        );

        if ( (\is_numeric($result) && $result !== 0) || (\is_numeric($status) && $status !== 0) ) {
            throw new BackupSchemaException("Could not create backup file: \n".(\implode("\n", $output)));
        }

        return $file;
    }

    /**
     * @param string $schemaName
     *
     * @return bool
     */
    public function hasSchemaBackup(string $schemaName): bool
    {
        return \file_exists($this->workDir."/{$schemaName}.sql");
    }

    /**
     * @param SchemaInterface $schema
     *
     * @throws BackupSchemaException
     */
    public function restoreSchema(SchemaInterface $schema): void
    {
        $file = $this->workDir."/{$schema->getName()}.sql";

        if ( ! \file_exists($file) ) {
            throw new BackupSchemaException("Backup file not found ({$file})");
        }

        list($h, $po, $u, $pa) = \array_values($this->connInfo);

        $result = \exec(
            "mysql --host $h --port $po --user $u --password=\"$pa\" {$schema->getName()} < $file",
            $output,
            $status
        );

        if ( (\is_numeric($result) && $result !== 0) || (\is_numeric($status) && $status !== 0) ) {
            throw new BackupSchemaException("Could not restore backup: \n".(\implode("\n", $output)));
        }
    }

    /**
     * @param SchemaInterface $schema
     * @param bool   $ifNotExists
     *
     * @return bool
     *
     * @throws \PDOException
     */
    public function createSchema(SchemaInterface $schema, bool $ifNotExists = true): bool
    {
        # Check whether schema already exists
        if ( $ifNotExists && $this->schemaExists($schema->getName()) ) {
            return false;
        }

        # Add SQL for schema creation
        $this->SQLQueue[$schema->getName()] = [$schema->getCreateStatement(!$ifNotExists)];

        # Check if schema has tables
        if ( $schema->hasTables() ) {

            # Select created database as reference
            $this->SQLQueue[$schema->getName()][] = "USE {$schema->getName()};";

            # Add SQL to create tables
            foreach ( $schema->getTables() as $table ) {
                $this->SQLQueue[$schema->getName()][] = $table->getCreateStatement();
            }
        }

        return true;
    }

    /**
     * @param SchemaInterface $schema
     *
     * @throws UpdateSchemaException
     * @throws BackupSchemaException
     */
    public function updateSchema(SchemaInterface $schema): void
    {
        if ( ! $this->schemaExists($schema->getName()) ) {
            throw new UpdateSchemaException("Schema '{$schema->getName()}' does not exists.");
        }

        if ( ! $schema->hasTables() ) {
            throw new UpdateSchemaException("Cannot update schema '{$schema->getName()}' without table definition.");
        }

        $this->createSchemaBackup($schema);

        # Select created database as reference
        $SQLQueue = [];

        $stmt   = $this->PDO->query("SHOW TABLES FROM `{$schema->getName()}`");
        $result = $stmt->fetchAll(\PDO::FETCH_NUM);

        $tablesNames = \array_column($result, 0);

        # First remove all tables that have no table definition
        foreach ( $tablesNames as $tableName ) {
            if ( ! $schema->hasTable($tableName) ) {
                $SQLQueue[] = "DROP TABLE `{$tableName}`;";
            }
        }

        # Create tables that are defined but not exists yet
        foreach ( $schema->getTables() as $table ) {
            if ( ! \in_array($table->getName(), $tablesNames) ) {
                $SQLQueue[] = $table->getCreateStatement();
            }
        }

        # Check table definition for changes and sync
        foreach ( $schema->getTables() as $table ) {
            if (! in_array($table->getName(), $tablesNames)) {
                continue;
            }

            # Update table constraints
            #$stmt = $this->PDO->query(
            #    "
            #  SELECT `CONSTRAINT_NAME`, `CONSTRAINT_TYPE`
            #  FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
            #  WHERE TABLE_SCHEMA = '{$schema->getName()}' AND TABLE_NAME = '{$table->getName()}';
            #"
            #);
            # TODO compare and update table constraints

            # Update table columns
            $stmt    = $this->PDO->query("DESCRIBE `{$schema->getName()}`.`{$table->getName()}`");
            $fields  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $changes = [];

            # Compare DB fields with defined tables
            foreach ( $fields as $field ) {
                list($name, $type, $null, $key, $default, $extra) = \array_values($field);

                # This field is not defined in table definition, so remove
                if ( ! $column = $table->getColumn($name) ) {
                    $changes[] = "DROP COLUMN `{$name}`;";
                    continue;
                }

                # Check for modification by table definitions
                $equal = (new Column($name, $type))
                    ->setNullable($null === 'YES')
                    ->setDefault($default)
                    ->equals($column);

                if ( ! $equal ) {
                    $changes[] = "MODIFY ".$column;
                }
            }

            # Compare table definitions with db fields
            foreach ($table->getColumns() as $column) {
                # Column is defined in table definition but not exists in DB yet, so create
                if ( ! \in_array($column->getName(), \array_column($fields, 'Field')) ) {
                    $changes[] = "ADD COLUMN ".$column;
                }
            }

            if ( $changes ) {
                $SQL        = "ALTER TABLE `{$table->getName()}`\n".\implode(",\n", $changes).';';
                $SQLQueue[] = $SQL;
            }
        }

        if ( $SQLQueue ) {
            $this->SQLQueue[$schema->getName()][] = "USE `{$schema->getName()}`;";
            $this->SQLQueue[$schema->getName()][] = \implode("\n", $SQLQueue);
        }
    }

    /**
     * @param SchemaInterface $schema
     *
     * @return bool
     */
    public function importData(SchemaInterface $schema): bool
    {
        if ( ! $schema->hasInsertStatements()) {
            return false;
        }

        $this->SQLQueue[$schema->getName()][] = "USE {$schema->getName()};";
        $this->SQLQueue[$schema->getName()][] = "START TRANSACTION;";

        foreach ( $schema->getInsertStatements() as $statement ) {
            $this->SQLQueue[$schema->getName()][] = $statement;
        }

        $this->SQLQueue[$schema->getName()][] = "COMMIT;";

        return true;
    }

    /**
     * @param string|null $schema
     */
    public function apply(string $schema = null): void
    {
        foreach ( $this->SQLQueue as $schemaName => $SQLs ) {

            if ( $schema && $schema !== $schemaName ) {
                continue;
            }

            $transaction = false;

            while ( $SQL = \array_shift($this->SQLQueue[$schemaName]) ) {

                if ( \preg_match('/BEGIN|START TRANSACTION/', $SQL) ) {
                    $transaction = true;
                }

                $this->PDO->exec($SQL);

                if ( $this->PDO->errorCode() !== '00000' ) {
                    $transaction && $this->PDO->exec('ROLLBACK;');
                    $this->SQLFails[] = [
                        'SQL' => $SQL,
                        'ERROR' => $this->PDO->errorInfo()
                    ];
                }
            }
        }

    }
}
