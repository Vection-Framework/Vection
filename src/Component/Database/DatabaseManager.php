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

namespace Vection\Component\Database;

use PDO;
use Vection\Component\Database\Exception\UpdateSchemaException;
use Vection\Contracts\Database\DatabaseInterface;
use Vection\Contracts\Database\SchemaInterface;

/**
 * Class DatabaseManager
 *
 * @package Vection\Component\Database
 *
 * @author  David Lung <vection@davidlung.de>
 */
class DatabaseManager implements DatabaseInterface
{
    /** @var PDO */
    protected $PDO;

    /** @var array */
    protected $SQLQueue;

    /** @var array */
    protected $SQLFails;

    /**
     * @param string $host
     * @param int    $port
     * @param string $user
     * @param string $password
     *
     * @return static
     */
    public static function create(string $host, int $port, string $user, string $password): self
    {
        return self::createFromDSN("mysql:host={$host};port={$port}", $user, $password);
    }

    /**
     * @param string $dsn
     * @param string $user
     * @param string $password
     *
     * @return static
     */
    public static function createFromDSN(string $dsn, string $user, string $password): self
    {
        return self::createFromPDO(new PDO($dsn, $user, $password));
    }

    /**
     * @param PDO $pdo
     *
     * @return static
     */
    public static function createFromPDO(PDO $pdo): self
    {
        return new self($pdo);
    }

    /**
     * DatabaseManager constructor.
     *
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        $this->PDO      = $pdo;
        $this->SQLQueue = [];
        $this->SQLFails = [];
    }

    # region Getter / Setter

    /**
     * @return array
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
        return count($this->SQLFails) > 0;
    }

    # endregion

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSchema(string $name): bool
    {
        return $this->PDO->query("SHOW DATABASES LIKE '{$name}'")->rowCount() > 0;
    }

    /**
     * @param string $name
     */
    public function dropSchema(string $name): void
    {
        if ( $this->hasSchema($name) ) {
            $this->PDO->exec("DROP DATABASES IF EXISTS `{$name}`");
        }
    }

    /**
     * @param SchemaInterface $schema
     * @param bool            $ifNotExists
     */
    public function createSchema(SchemaInterface $schema, bool $ifNotExists = true): void
    {
        if ( $ifNotExists && $this->hasSchema($schema->getName()) ) {
            return;
        }

        $this->SQLQueue[$schema->getName()] = [$schema->getCreateStatement(!$ifNotExists)];

        if ( $schema->hasTables() ) {

            $this->SQLQueue[$schema->getName()][] = "USE {$schema->getName()};";

            foreach ( $schema->getTables() as $table ) {
                $this->SQLQueue[$schema->getName()][] = $table->getCreateStatement();
            }
        }
    }

    /**
     * @param SchemaInterface $schema
     *
     * @throws UpdateSchemaException
     */
    public function updateSchema(SchemaInterface $schema): void
    {
        if ( ! $this->hasSchema($schema->getName()) ) {
            throw new UpdateSchemaException("Schema '{$schema->getName()}' does not exists.");
        }

        # Select created database as reference
        $SQLQueue = [];

        $stmt   = $this->PDO->query("SHOW TABLES FROM `{$schema->getName()}`");
        $result = $stmt->fetchAll(PDO::FETCH_NUM);

        $existingTablesNames = array_column($result, 0);

        # Create tables that are defined but not exists yet
        foreach ( $schema->getTables() as $table ) {
            if ( ! in_array($table->getName(), $existingTablesNames, true)) {
                $SQLQueue[] = $table->getCreateStatement();
            }
        }

        # Check table definition for changes and sync
        foreach ( $schema->getTables() as $table ) {
            if (! in_array($table->getName(), $existingTablesNames, true)) {
                continue;
            }

            # TODO compare and update table constraints

            # Update table columns
            $stmt    = $this->PDO->query("DESCRIBE `{$schema->getName()}`.`{$table->getName()}`");
            $fields  = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $changes = [];

            # Compare DB fields with defined tables
            foreach ( $fields as $field ) {
                list($name, $type, $null, $key, $default, $extra) = array_values($field);

                # This field is not defined in table definition, so remove
                if ( ! $column = $table->getColumn($name) ) {
                    $changes[] = "DROP COLUMN `{$name}`;";
                    continue;
                }

                $hasDifference = $column->getName() !== $name
                    || strtoupper($column->getType()) !== strtoupper($type)
                    || $column->isNullable() !== ($null === 'YES')
                    || $column->getDefault() !== $default
                ;

                if ($hasDifference) {
                    $changes[] = 'MODIFY '.$column;
                }
            }

            # Compare table definitions with db fields
            foreach ($table->getColumns() as $column) {
                # Column is defined in table definition but not exists in DB yet, so create
                if ( ! in_array($column->getName(), array_column($fields, 'Field'), true)) {
                    $changes[] = 'ADD COLUMN '.$column;
                }
            }

            if ($changes) {
                $SQLQueue[] = "ALTER TABLE `{$table->getName()}`".PHP_EOL.implode(','.PHP_EOL, $changes).';';
            }
        }

        if ( $SQLQueue ) {
            $this->SQLQueue[$schema->getName()][] = "USE `{$schema->getName()}`;";
            $this->SQLQueue[$schema->getName()][] = implode(PHP_EOL, $SQLQueue);
        }
    }

    /**
     * @param string|null $schema
     */
    public function apply(string $schema = null): void
    {
        foreach ($this->SQLQueue as $schemaName => $SQLs) {

            if ( $schema && $schema !== $schemaName ) {
                continue;
            }

            $transaction = false;

            while ( $SQL = array_shift($this->SQLQueue[$schemaName]) ) {

                if ( preg_match('/BEGIN|START TRANSACTION/', $SQL) ) {
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
