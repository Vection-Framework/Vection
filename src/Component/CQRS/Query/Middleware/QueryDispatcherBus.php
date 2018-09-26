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

namespace Vection\Component\CQRS\Query\Middleware;

use Vection\Contracts\CQRS\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\CQRS\Query\QueryBusSequenceInterface;
use Vection\Contracts\CQRS\Query\QueryInterface;
use Vection\Contracts\CQRS\Query\QueryResolverInterface;
use Vection\Contracts\CQRS\Query\ReadModelInterface;

/**
 * Class QueryDispatcherBus
 *
 * @package Vection\Component\CQRS\Query\Middleware
 */
class QueryDispatcherBus implements QueryBusMiddlewareInterface
{
    /** @var QueryResolverInterface */
    protected $resolver;

    /**
     * DispatcherQueryBus constructor.
     *
     * @param QueryResolverInterface $resolver
     */
    public function __construct(QueryResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param QueryInterface            $query
     * @param QueryBusSequenceInterface $sequence
     *
     * @return ReadModelInterface|null
     *
     * @throws \Exception
     */
    public function __invoke(QueryInterface $query, QueryBusSequenceInterface $sequence)
    {
        $handler = $this->resolver->resolve($query);

        return $handler($query);
    }
}