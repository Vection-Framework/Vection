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

declare(strict_types = 1);

namespace Vection\Contracts\Database;

use Vection\Contracts\Database\Exception\BackupSchemaExceptionInterface;
use Vection\Contracts\Database\Exception\UpdateSchemaExceptionInterface;

/**
 * Interface DatabaseInterface
 *
 * @package Vection\Contracts\Database
 */
interface DatabaseInterface
{
    /**
     * @return string[]
     */
    public function getSQLQueue(): array;

    /**
     *
     * @return string[][]
     */
    public function getSQLFails(): array;

    /**
     *
     * @return bool
     */
    public function hasSQLFails(): bool;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSchema(string $name): bool;

    /**
     * @param string $name
     */
    public function dropSchema(string $name): void;

    /**
     * @param SchemaInterface $schema
     * @param bool            $ifNotExists
     */
    public function createSchema(SchemaInterface $schema, bool $ifNotExists = true): void;

    /**
     * @param SchemaInterface $schema
     *
     * @throws UpdateSchemaExceptionInterface
     * @throws BackupSchemaExceptionInterface
     */
    public function updateSchema(SchemaInterface $schema): void;

    /**
     * @param string|null $schema
     */
    public function apply(string $schema = null): void;
}
