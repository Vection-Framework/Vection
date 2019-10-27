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

namespace Vection\Contracts\MessageBus\Query;

/**
 * Class QueryBusInterface
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryBusInterface
{
    /**
     * Attaches a middleware to this bus.
     *
     * @param QueryBusMiddlewareInterface $queryBusMiddleware
     */
    public function attach(QueryBusMiddlewareInterface $queryBusMiddleware): void;

    /**
     * Handle the execution of all attached middleware in order
     * they were attached to this bus.
     *
     * @param QueryInterface $message
     *
     * @return ReadModelInterface|null
     *
     */
    public function handle(QueryInterface $message): ? ReadModelInterface;

    /**
     * Alias for the handle method.
     *
     * @param QueryInterface $message
     *
     * @return ReadModelInterface|null
     *
     * @see QueryBusInterface::handle()
     */
    public function __invoke(QueryInterface $message): ? ReadModelInterface;
}
