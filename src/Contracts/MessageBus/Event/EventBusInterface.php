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

namespace Vection\Contracts\MessageBus\Event;

/**
 * Interface EventBusInterface
 *
 * @package Vection\Contracts\MessageBus\Event
 */
interface EventBusInterface
{
    /**
     * Attaches a middleware to this bus.
     *
     * @param EventBusMiddlewareInterface $queryBusMiddleware
     */
    public function attach(EventBusMiddlewareInterface $queryBusMiddleware): void;

    /**
     * Handle the execution of all attached middleware in order
     * they were attached to this bus.
     *
     * @param EventInterface $message
     *
     */
    public function publish(EventInterface $message): void;

    /**
     * Alias for the handle method.
     *
     * @param EventInterface $message
     *
     * @see EventBusInterface::publish()
     */
    public function __invoke(EventInterface $message): void;
}
