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

namespace Vection\Component\Messenger\Service\Query;

use ArrayObject;
use IteratorAggregate;
use Traversable;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;

/**
 * Class ReadModelCollection
 *
 * @package Vection\Component\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
class ReadModelCollection extends ReadModel implements IteratorAggregate
{
    /** @var ReadModelInterface[] */
    protected $models;

    /** @var integer */
    protected $total;

    /**
     * ReadModelCollection constructor.
     *
     * @param array $models
     * @param int   $total
     */
    public function __construct(array $models, int $total = 0)
    {
        $this->models  = $models;
        $this->total   = $total ?: count($models);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $collection = [];

        foreach ($this->models as $index => $model) {
            $collection[$index] = $model->toArray();
        }

        return $collection;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return (new ArrayObject($this->models))->getIterator();
    }

    /**
     * @param string $className
     * @param array  $records
     * @param int    $total
     *
     * @return ReadModelCollection
     */
    public static function of(string $className, array $records, int $total = 0): ReadModelCollection
    {
        $models = [];

        foreach ($records as $record) {
            $models[] = new $className($record);
        }

        return new ReadModelCollection($models, $total);
    }

    /**
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return count($this->models) < 1;
    }

    /**
     * @return ReadModelInterface[]
     */
    public function getModels(): array
    {
        return $this->models;
    }
}
