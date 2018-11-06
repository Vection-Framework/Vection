<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Query\Middleware;

use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryBusSequenceInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\QueryResolverInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class QueryDispatcherBus
 *
 * @package Vection\Component\MessageBus\Query\Middleware
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