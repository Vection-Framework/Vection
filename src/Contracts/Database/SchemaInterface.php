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

namespace Vection\Contracts\Database;

/**
 * Interface SchemaInterface
 *
 * @package Vection\Contracts\Database
 */
interface SchemaInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param TableInterface $table
     *
     * @return SchemaInterface
     */
    public function addTable(TableInterface $table): SchemaInterface;

    /**
     * @return TableInterface[]
     */
    public function getTables(): array;

    /**
     * @return bool
     */
    public function hasTables(): bool;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasTable(string $name): bool;

    /**
     * Returns the create statement as database language representation.
     *
     * @param bool $withDrop
     *
     * @return string
     */
    public function getCreateStatement(bool $withDrop = false): string;
}
