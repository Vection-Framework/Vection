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
use Vection\Contracts\Database\TableInterface;

/**
 * Class Table
 *
 * @package Vection\Component\Database
 */
class Table implements TableInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $comment;

    /** @var array */
    protected $keys;

    /** @var array */
    protected $indexes;

    /** @var Column[] */
    protected $columns;

    /** @var string */
    protected $engine;

    /** @var string */
    protected $charSet;

    /** @var string */
    protected $collate;

    /**
     * Table constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->keys = [];
        $this->indexes = [];
        $this->columns = [];
        $this->charSet = 'utf8';
        $this->collate = 'utf8_unicode_ci';
        $this->engine = 'InnoDB';
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
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @param string $comment
     *
     * @return TableInterface
     */
    public function setComment(string $comment): TableInterface
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param array $keys
     *
     * @return TableInterface
     */
    public function setKeys(array $keys): TableInterface
    {
        $this->keys = $keys;
        return $this;
    }

    /**
     * @param string $column
     *
     * @return TableInterface
     */
    public function setPrimaryKey(string $column): TableInterface
    {
        $this->keys['primary'] = $column;
        return $this;
    }

    /**
     * @param array  $columns
     *
     * @return TableInterface
     */
    public function setPrimaryCompositeKey(array $columns): TableInterface
    {
        $this->keys['primary'] = $columns;
        return $this;
    }

    /**
     * @param string $column
     *
     * @return TableInterface
     */
    public function setUniqueKey(string $column): TableInterface
    {
        $this->keys['unique'] = $column;
        return $this;
    }

    /**
     * @return array
     */
    public function getIndexes(): array
    {
        return $this->indexes;
    }

    /**
     * @param array $indexes
     *
     * @return TableInterface
     */
    public function setIndexes(array $indexes): TableInterface
    {
        $this->indexes = $indexes;
        return $this;
    }

    /**
     * @param ColumnInterface $column
     *
     * @return TableInterface
     */
    public function addColumn(ColumnInterface $column): TableInterface
    {
        $this->columns[$column->getName()] = $column;
        return $this;
    }

    /**
     * @return Column[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @param string $name
     *
     * @return ColumnInterface|null
     */
    public function getColumn(string $name): ? ColumnInterface
    {
        return $this->columns[$name] ?? null;
    }

    /**
     * @return string
     */
    public function getCharSet(): string
    {
        return $this->charSet;
    }

    /**
     * @param string $charSet
     *
     * @return TableInterface
     */
    public function setCharSet(string $charSet): TableInterface
    {
        $this->charSet = $charSet;
        return $this;
    }

    /**
     * @return string
     */
    public function getCollate(): string
    {
        return $this->collate;
    }

    /**
     * @param string $collate
     *
     * @return TableInterface
     */
    public function setCollate(string $collate): TableInterface
    {
        $this->collate = $collate;
        return $this;
    }

    /**
     * @return string
     */
    public function getEngine(): string
    {
        return $this->engine;
    }

    /**
     * @param string $engine
     *
     * @return TableInterface
     */
    public function setEngine(string $engine): TableInterface
    {
        $this->engine = $engine;
        return $this;
    }

    # endregion

    /**
     * Sets all table definition properties from given
     * definition array. This method is basically used for
     * definition content provided by table definition files.
     *
     * @param array $definition
     *
     * @return TableInterface
     */
    public function fromArray(array $definition): TableInterface
    {
        $definition = $definition['table'];

        isset($definition['comment']) AND $this->setComment($definition['comment']);
        isset($definition['keys'])    AND $this->setKeys($definition['keys']);
        isset($definition['engine'])  AND $this->setEngine($definition['engine']);
        isset($definition['charset']) AND $this->setCharSet($definition['charset']);
        isset($definition['collate']) AND $this->setCollate($definition['collate']);

        foreach( $definition['columns'] as $name => $colDef ){
            $column = new Column($name, $colDef['type']);
            $column->fromArray($colDef);
            $this->addColumn($column);
        }

        return $this;
    }

    /**
     * Returns the create statement as database language representation.
     *
     * @param bool $drop
     *
     * @return string
     */
    public function getCreateStatement(bool $drop = false): string
    {
        $def = [];

        $drop && ($def[] = "DROP TABLE IF EXISTS `{$this->name}`;");

        $def[] = "CREATE TABLE IF NOT EXISTS `{$this->name}` (";

        $colDef[] = \implode(",\n", $this->columns);

        if( isset($this->keys['primary']) ){
            $pkContent = $this->keys['primary'];

            if( \is_array($pkContent) ){
                $pkContent = \implode('`,`', $this->keys['primary']);
            }

            $colDef[] = "PRIMARY KEY (`{$pkContent}`)";
        }

        foreach( $this->keys['unique'] ?? [] as $key ){
            $parts = \explode(' ', $key);
            $colDef[] = "UNIQUE KEY `{$parts[0]}`".(isset($parts[1]) ? " (`{$parts[1]}`)":'');
        }

        # TODO handle index

        $def[] = \implode(",\n", $colDef);
        $def[] = ") ENGINE = ".$this->engine;
        $def[] = "DEFAULT CHARSET = ". $this->charSet;
        $def[] = "COLLATE = ".$this->collate;

        return \implode("\n", $def).';';
    }
}