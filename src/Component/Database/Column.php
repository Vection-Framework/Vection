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

    /** @var bool */
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
        $this->name = trim($name);
        $this->type = trim($type);
        $this->nullable = false;
        $this->extra = '';
    }

    # region Getter / Setter

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
     * @return string
     */
    public function getTypeName(): string
    {
        if( \strpos($this->type, '(') === false ){
            return $this->type;
        }

        return \substr($this->type, 0, \strpos($this->type, '('));
    }

    /**
     * @return array
     */
    public function getTypeSpecification(): array
    {
        if( \strpos($this->type, '(') === false ){
            return [];
        }

        $spec = \substr($this->type, \strpos($this->type, '(')+1, -1);

        if( \strpos($spec, ',') === false ){
            return [$spec];
        }

        $spec = \explode(',', $spec);
        array_walk($spec, 'trim');

        return $spec;
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
    public function fromArray(array $definition): ColumnInterface
    {
        isset($definition['nullable']) && $definition['nullable'] && $this->setNullable(true);
        isset($definition['collate'])  && $this->setCollate($definition['collate']);
        isset($definition['extra'])    && $this->setExtra($definition['extra']);

        if( \array_key_exists('default', $definition) ){
            $this->setDefault(
                \is_numeric($definition['default']) ? (string) $definition['default'] : $definition['default']
            );
        }

        return $this;
    }

    /**
     * Compares column definition with an other column.
     * Returns true if the columns has same definition.
     *
     * @param ColumnInterface $column
     *
     * @return bool
     */
    public function equals(ColumnInterface $column): bool
    {
        return
            $this->name === $column->getName()
            && $this->getTypeName() === $column->getTypeName()
            && $this->getDefault() === $column->getDefault()
            && $column->getCollate() === $this->getCollate()
            && !\array_diff(
                $this->getTypeSpecification(),
                $column->getTypeSpecification()
            )
        ;
    }

    /**
     * Returns the database lang definition of this column.
     *
     * @return string
     */
    public function getDefinition(): string
    {
        $def = ["`{$this->name}`"];

        $def[] = $this->type;

        if( $this->collate ){
            $def[] = 'COLLATE '.$this->collate;
        }

        $def[] = $this->nullable ? 'NULL' : 'NOT NULL';

        if( ($this->nullable && $this->default === null) || ($this->default || is_string($this->default)) ){
            $def[] = 'DEFAULT '.($this->default === null ? 'NULL'
                : (\in_array($this->default, ['CURRENT_TIMESTAMP']) ? $this->default : "'{$this->default}'"));
        }

        if( $this->extra ){
            $def[] = \strtoupper($this->extra);
        }

        return \implode("\t", $def);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDefinition();
    }

}