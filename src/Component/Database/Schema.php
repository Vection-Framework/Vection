<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Database;

use Vection\Contracts\Database\SchemaInterface;
use Vection\Contracts\Database\TableInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Database
 */
class Schema implements SchemaInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $charSet;

    /** @var string */
    protected $collate;

    /** @var TableInterface[] */
    protected $tables;

    /** @var string[] */
    protected $insert;

    /**
     * Schema constructor.
     *
     * @param string $name
     *
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->charSet = 'utf8';
        $this->collate = 'utf8_unicode_ci';
        $this->tables = [];
        $this->insert = [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param TableInterface $table
     *
     * @return SchemaInterface
     */
    public function addTable(TableInterface $table): SchemaInterface
    {
        $this->tables[$table->getName()] = $table;
        return $this;
    }

    /**
     * @return TableInterface[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @return bool
     */
    public function hasTables(): bool
    {
        return \count($this->tables) > 0;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasTable(string $name): bool
    {
        return isset($this->tables[$name]);
    }

    /**
     * @param string $statement
     *
     * @return SchemaInterface
     */
    public function addInsertStatement(string $statement): SchemaInterface
    {
        $this->insert[] = $statement;
        return $this;
    }

    /**
     * @param array $statements
     *
     * @return SchemaInterface
     */
    public function setInsertStatements(array $statements): SchemaInterface
    {
        $this->insert = $statements;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasInsertStatements(): bool
    {
        return \count($this->insert) > 0;
    }

    /**
     * @return string[]
     */
    public function getInsertStatements(): array
    {
        return $this->insert;
    }

    /**
     * @param string $filePath
     *
     * @return SchemaInterface
     */
    public function addDataInsertFile(string $filePath): SchemaInterface
    {
        foreach( \glob($filePath) as $file ){
            $this->addInsertStatement(\file_get_contents($file));
        }

        return $this;
    }

    /**
     * @param array $definition
     *
     * @return SchemaInterface
     */
    public function loadTableFromArray(array $definition): SchemaInterface
    {
        $table = new Table($definition['table']['name']);
        $table->fromArray($definition);
        $this->addTable($table);
        return $this;
    }

    /**
     * @param string $filePath
     *
     * @return SchemaInterface
     */
    public function loadTableFromFile(string $filePath): SchemaInterface
    {
        foreach( \glob($filePath) as $file ){

            $definition = [];

            if( \in_array(\pathinfo($file, PATHINFO_EXTENSION), ['yml', 'yaml']) ){
                $definition = \yaml_parse_file($file);

                if( ! $definition ){
                    throw new \RuntimeException(
                        "Vection.Database.Schema: YAML ERROR - malformed file ({$file})"
                    );
                }
            }

            if( ! $definition ){
                throw new \RuntimeException(
                    "Vection.Database.Schema: Unsupported file type ({$file})"
                );
            }

            $this->loadTableFromArray($definition);
        }

        return $this;
    }

    /**
     * Returns the create statement as database language representation.
     *
     * @param bool $withDrop
     *
     * @return string
     */
    public function getCreateStatement(bool $withDrop = false): string
    {
        $SQL = [];

        $withDrop AND ($SQL[] = "DROP DATABASE IF EXISTS `{$this->name}`;");

        $SQL[] = "CREATE DATABASE `{$this->name}` CHARACTER SET {$this->charSet} COLLATE {$this->collate};";

        return \implode("\n", $SQL);
    }
}