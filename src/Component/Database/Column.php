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

use Vection\Contracts\Database\ColumnInterface;

/**
 * Class Column
 *
 * @package Vection\Component\Database
 */
class Column implements ColumnInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $type;

    /** @var boolean */
    protected $nullable;

    /** @var string */
    protected $default;

    /** @var string */
    protected $collate;

    /** @var string */
    protected $extra;

    /**
     * Column constructor.
     *
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, string $type)
    {
        $this->name     = trim($name);
        $this->type     = trim($type);
        $this->nullable = false;
        $this->extra    = '';
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @param bool $nullable
     *
     * @return ColumnInterface
     */
    public function setNullable(bool $nullable): ColumnInterface
    {
        $this->nullable = $nullable;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefault(): ? string
    {
        return $this->default;
    }

    /**
     * @param string $default
     *
     * @return ColumnInterface
     */
    public function setDefault(? string $default): ColumnInterface
    {
        $this->default = $default;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollate(): ? string
    {
        return $this->collate;
    }

    /**
     * @param string $collate
     *
     * @return ColumnInterface
     */
    public function setCollate(string $collate): ColumnInterface
    {
        $this->collate = $collate;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtra(): string
    {
        return $this->extra;
    }

    /**
     * @param string $extra
     *
     * @return ColumnInterface
     */
    public function setExtra(string $extra): ColumnInterface
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * Returns the database lang definition of this column.
     *
     * @return string
     */
    public function getDefinition(): string
    {
        $def = ["`{$this->name}`", $this->type];

        if ( $this->collate ) {
            $def[] = 'COLLATE '.$this->collate;
        }

        $def[] = $this->nullable ? 'NULL' : 'NOT NULL';

        if ($this->default === null && $this->nullable) {
            $def[] = 'DEFAULT NULL';
        } else if ($this->default !== null) {
            $specialValues = ['CURRENT_TIMESTAMP'];
            $def[]         = 'DEFAULT '.(in_array($this->default, $specialValues, true) ? $this->default : "'{$this->default}'");
        }

        if ( $this->extra ) {
            $def[] = strtoupper($this->extra);
        }

        return implode("\t", $def);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDefinition();
    }

}
