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

namespace Vection\Component\MessageBus\Query;

use Vection\Contracts\MessageBus\Query\QueryBusInterface;
use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class QueryBus
 *
 * @package Vection\Component\MessageBus\Query
 */
class QueryBus implements QueryBusInterface
{
    /**
     * This property contains all attached middleware buses.
     *
     * @var QueryBusMiddlewareInterface[]
     */
    protected $middleware = [];

    /**
     * @inheritdoc
     */
    public function __invoke(QueryInterface $message): ?ReadModelInterface
    {
        return $this->handle($message);
    }

    /**
     * @inheritdoc
     */
    public function handle(QueryInterface $message): ?ReadModelInterface
    {
        $sequence = new QueryBusSequence($this->middleware);

        return $sequence->invokeNext($message);
    }

    /**
     * @inheritdoc
     */
    public function attach(QueryBusMiddlewareInterface $queryBusMiddleware): void
    {
        $this->middleware[] = $queryBusMiddleware;
    }
}