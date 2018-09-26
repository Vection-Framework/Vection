<?php

namespace Vection\Component\CQRS\Query;

use Vection\Contracts\CQRS\Query\ReadModelInterface;

/**
 * Class ReadModelCollection
 *
 * @package Vection\Component\CQRS\Query
 */
class ReadModelCollection extends ReadModel implements \IteratorAggregate
{
    /** @var ReadModel[] */
    protected $listKey = 'items';

    /** @var int */
    protected $total = 0;

    /** @var ReadModelInterface[] */
    protected $items;

    /**
     * ReadModelCollection constructor.
     *
     * @param array  $items
     * @param int    $totalCount
     * @param string $itemListKey
     */
    public function __construct(array $items, int $totalCount = 0, string $itemListKey = 'items')
    {
        $this->items = $items;
        $this->total = $totalCount ?: \count($items);
        $this->listKey = $itemListKey;
    }

    /**
     * @param string $readModel
     * @param array  $records
     * @param int    $totalCount
     * @param string $itemListKey
     *
     * @return ReadModelCollection
     */
    public static function of(string $readModel, array $records, int $totalCount = 0, string $itemListKey = 'items'): ReadModelCollection
    {
        $items = [];
        foreach ( $records ?? [] as $row ) {
            $items[] = new $readModel($row);
        }

        return new ReadModelCollection($items, $totalCount, $itemListKey);
    }

    /**
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return \count($this->items) < 1;
    }

    /**
     * @return ReadModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param $key
     *
     * @return null|ReadModel
     */
    public function getItem($key): ?ReadModel
    {
        return $this->items[(string)$key] ?: null;
    }

    /**
     * Retrieve an external iterator
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return \Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return ( new \ArrayObject($this->items) )->getIterator();
    }
}