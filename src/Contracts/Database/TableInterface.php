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

namespace Vection\Contracts\Database;

/**
 * Interface TableInterface
 *
 * @package Vection\Contracts\Database
 */
interface TableInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getComment(): string;

    /**
     * @param string $comment
     *
     * @return TableInterface
     */
    public function setComment(string $comment): TableInterface;

    /**
     * @return array
     */
    public function getKeys(): array;

    /**
     * @param array $keys
     *
     * @return TableInterface
     */
    public function setKeys(array $keys): TableInterface;

    /**
     * @param string $column
     *
     * @return TableInterface
     */
    public function setPrimaryKey(string $column): TableInterface;

    /**
     * @param string $column
     *
     * @return TableInterface
     */
    public function setUniqueKey(string $column): TableInterface;

    /**
     * @return array
     */
    public function getIndexes(): array;

    /**
     * @param array $indexes
     *
     * @return TableInterface
     */
    public function setIndexes(array $indexes): TableInterface;

    /**
     * @param ColumnInterface $column
     *
     * @return TableInterface
     */
    public function addColumn(ColumnInterface $column): TableInterface;

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array;

    /**
     * @param string $name
     *
     * @return ColumnInterface|null
     */
    public function getColumn(string $name): ? ColumnInterface;

    /**
     * @return string
     */
    public function getCharSet(): string;

    /**
     * @param string $charSet
     *
     * @return TableInterface
     */
    public function setCharSet(string $charSet): TableInterface;

    /**
     * @return string
     */
    public function getCollate(): string;

    /**
     * @param string $collate
     *
     * @return TableInterface
     */
    public function setCollate(string $collate): TableInterface;

    /**
     * @return string
     */
    public function getEngine(): string;

    /**
     * @param string $engine
     *
     * @return TableInterface
     */
    public function setEngine(string $engine): TableInterface;

    /**
     * Sets all table definition properties from given
     * definition array. This method is basically used for
     * definition content provided by table definition files.
     *
     * @param array $definition
     *
     * @return TableInterface
     */
    public function fromArray(array $definition): TableInterface;

    /**
     * Returns the create statement as database language representation.
     *
     * @param bool $drop
     *
     * @return string
     */
    public function getCreateStatement(bool $drop = false): string;
}