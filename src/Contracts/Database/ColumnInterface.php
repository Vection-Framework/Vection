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
 * Interface ColumnInterface
 *
 * @package Vection\Contracts\Database
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTypeName(): string;

    /**
     * @return array
     */
    public function getTypeSpecification(): array;

    /**
     * @return bool
     */
    public function isNullable(): bool;

    /**
     * @param bool $nullable
     *
     * @return ColumnInterface
     */
    public function setNullable(bool $nullable): ColumnInterface;

    /**
     * @return string
     */
    public function getDefault(): ? string;
    /**
     * @param string $default
     *
     * @return ColumnInterface
     */
    public function setDefault(? string $default): ColumnInterface;

    /**
     * @return string
     */
    public function getCollate(): ? string;

    /**
     * @param string $collate
     *
     * @return ColumnInterface
     */
    public function setCollate(string $collate): ColumnInterface;

    /**
     * @return string
     */
    public function getExtra(): string;

    /**
     * @param string $extra
     *
     * @return ColumnInterface
     */
    public function setExtra(string $extra): ColumnInterface;

    # endregion

    /**
     * Sets all column definition properties from given
     * definition array. This method is basically used for
     * definition content provided by table definition files.
     *
     * @param array $definition
     *
     * @return ColumnInterface
     */
    public function fromArray(array $definition): ColumnInterface;

    /**
     * Compares column definition with an other column.
     * Returns true if the columns has same definition.
     *
     * @param ColumnInterface $column
     *
     * @return bool
     */
    public function equals(ColumnInterface $column): bool;

    /**
     * Returns the database lang definition of this column.
     *
     * @return string
     */
    public function getDefinition(): string;

    /**
     * @return string
     */
    public function __toString(): string;

}