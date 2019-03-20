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
    public function schemaExists(string $name): bool;

    /**
     * @param string $name
     */
    public function dropSchema(string $name): void;

    /**
     * @param SchemaInterface $schema
     *
     * @return string
     *
     * @throws BackupSchemaExceptionInterface
     */
    public function createSchemaBackup(SchemaInterface $schema): string;

    /**
     * @param string $schemaName
     *
     * @return bool
     */
    public function hasSchemaBackup(string $schemaName): bool;

    /**
     * @param SchemaInterface $schema
     *
     * @throws BackupSchemaExceptionInterface
     */
    public function restoreSchema(SchemaInterface $schema): void;

    /**
     * @param SchemaInterface $schema
     * @param bool            $ifNotExists
     *
     * @return bool
     *
     * @throws \PDOException
     */
    public function createSchema(SchemaInterface $schema, bool $ifNotExists = true): bool;

    /**
     * @param SchemaInterface $schema
     *
     * @throws UpdateSchemaExceptionInterface
     * @throws BackupSchemaExceptionInterface
     */
    public function updateSchema(SchemaInterface $schema): void;

    /**
     * @param SchemaInterface $schema
     *
     * @return bool
     */
    public function importData(SchemaInterface $schema): bool;

    /**
     * @param string|null $schema
     */
    public function apply(string $schema = null): void;
}