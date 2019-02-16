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

namespace Vection\Contracts\Event;

/**
 * Interface EventDispatcherInterface
 *
 * @package Vection\Contracts\Event
 */
interface EventDispatcherInterface
{
    /**
     * Sets a callback for handler instantiation.
     *
     * The first parameter that is passed to the closure
     * is the full qualified class name of the handler.
     * The closure must return an instance of the given handler class.
     *
     * @param callable $closure A closure for handler instantiation.
     */
    public function setHandlerFactoryCallback(callable $closure): void;

    /**
     * Adds an event handler for the given event.
     *
     * The handler can be a callback as function or object method call
     * or an array for the full qualified class name and method name of the handler.
     * If the callback is a function, the event object is passed as the first
     * parameter to the function otherwise the event object is passed to the
     * method defined in the array.
     * If the handler is an array with FQCN and method name, this class will be
     * instantiated in default way by this event manager or by a custom handler factory
     *
     * @param string         $event
     * @param array|callable $handler
     * @param int            $priority
     */
    public function addHandler(string $event, $handler, int $priority = 0): void;

    /**
     * Triggers the given event an notify all registered handlers.
     *
     * @param EventInterface $event
     */
    public function dispatch(EventInterface $event): void;
}