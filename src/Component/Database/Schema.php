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
    protected $charset;

    /** @var string */
    protected $collate;

    /** @var TableInterface[] */
    protected $tables;

    /**
     * Schema constructor.
     *
     * @param string $name
     * @param string $charset
     * @param string $collate
     */
    public function __construct(string $name, string $charset = 'utf8', string $collate = 'utf8_unicode_ci')
    {
        $this->name    = $name;
        $this->charset = $charset;
        $this->collate = $collate;
        $this->tables  = [];
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function addTable(TableInterface $table): SchemaInterface
    {
        $this->tables[$table->getName()] = $table;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    /**
     * @inheritDoc
     */
    public function hasTables(): bool
    {
        return \count($this->tables) > 0;
    }

    /**
     * @inheritDoc
     */
    public function hasTable(string $name): bool
    {
        return isset($this->tables[$name]);
    }

    /**
     * @inheritDoc
     */
    public function getCreateStatement(bool $dropIfExists = false): string
    {
        $SQL = ["CREATE DATABASE `{$this->name}` CHARACTER SET {$this->charset} COLLATE {$this->collate};"];

        if ($dropIfExists) {
            array_unshift($SQL, "DROP DATABASE IF EXISTS `{$this->name}`;");
        }

        return implode("\n", $SQL);
    }
}
