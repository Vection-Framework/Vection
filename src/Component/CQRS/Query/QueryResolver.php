<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Query;

use Vection\Contracts\CQRS\Query\QueryHandlerFactoryInterface;
use Vection\Contracts\CQRS\Query\QueryHandlerInterface;
use Vection\Contracts\CQRS\Query\QueryInterface;
use Vection\Contracts\CQRS\Query\QueryResolverInterface;

/**
 * Class QueryResolver
 *
 * @package Vection\Component\CQRS\Query
 */
class QueryResolver implements QueryResolverInterface
{
    /** @var QueryHandlerFactoryInterface */
    protected $factory;

    /** @var string[] */
    protected $queryHandlerMap;

    /**
     * QueryResolver constructor.
     *
     * @param null|QueryHandlerFactoryInterface $factory
     * @param array                             $queryHandlerMap
     */
    public function __construct(? QueryHandlerFactoryInterface $factory = null, array $queryHandlerMap = [])
    {
        $this->queryHandlerMap = $queryHandlerMap;
        $this->factory = $factory;
    }

    /**
     * @param string $queryFQCN
     * @param string $handlerFQCN
     */
    public function register(string $queryFQCN, string $handlerFQCN): void
    {
        $this->queryHandlerMap[$queryFQCN] = $handlerFQCN;
    }

    /**
     * @inheritdoc
     */
    public function resolve(QueryInterface $query): QueryHandlerInterface
    {
        $className = $this->queryHandlerMap[get_class($query)] ?? null;

        if ( ! $className ) {
            throw new \RuntimeException(
                'There is no handler registered for query "' . get_class($query) . '"'
            );
        }

        if ( ! array_key_exists(QueryHandlerInterface::class, class_implements($className)) ) {
            throw new \RuntimeException(
                'Invalid query handler mapping (type mismatch) for query "' . get_class($query) . '"'
            );
        }

        return $this->factory ? $this->factory->create($className) : new $className();
    }
}