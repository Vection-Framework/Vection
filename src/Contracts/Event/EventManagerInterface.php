<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
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
interface EventManagerInterface
{
    /**
     * This method is an alternative way to register event classes
     * instead using the EventManager::addEventListener method.
     * Adds an array that contains all event names mapped by event FQCNs.
     *
     * E.g. [.., FQCN => eventName, ..]
     *
     * @param array $mapping
     */
    public function setEventClassMap(array $mapping): void;

    /**
     * This method is an alternative way to register event listeners
     * instead using the EventManager::addEventListener method.
     * Add an array that contains all listener data mapped by event names.
     *
     * E.g. [.., eventName => [.., [handler<callable>, priority<int>], ..]
     *
     * @param array $mapping
     */
    public function setEventListenerMap(array $mapping): void;

    /**
     * Sets a callback for listener instantiation.
     *
     * The first parameter that is passed to the closure
     * is the full qualified class name of the listener.
     * The closure must return an instance of the given listener class.
     *
     * @param callable $closure A closure for listener instantiation.
     */
    public function setEventListenerFactory(callable $closure): void;

    /**
     * Adds an event listener for the given event.
     *
     * The listener can be a callback as function or object method call
     * or an array for the full qualified class name and method name of the handler.
     * If the callback is a function, the event object is passed as the first
     * parameter to the function otherwise the event object is passed to the
     * method defined in the array.
     * If the listener is an array with FQCN and handler method name, this class will be
     * instantiated in default way by this event manager or by a custom handler factory
     *
     * @see EventManagerInterface::setEventListenerFactory()
     *
     * @param string         $event An unique name of the event.
     * @param array|callable $listener A callable listener with handler method.
     * @param int            $priority The priority of the event rising.
     */
    public function addEventListener(string $event, $listener, int $priority = 0): void;

    /**
     * Sets the event name matching wildcard separator.
     * The setting of this separator will enable the event name matching by
     * name wildcard. The wildcards are limited to the given separator.
     *
     * E.g. Setting the separator to ".", the fire of the event name "vection.user"
     * will notify also all listener which are registered for
     * "vection.user.create", "vection.user.updated" etc.
     *
     * @param string $separator
     */
    public function setWildcardSeparator(string $separator): void;

    /**
     * Fires the given event name or event object and notifies all registered listeners.
     * The passed event can be either the event name or the event object.
     * If the event is an event name as string, this method will instantiate a default event object
     * which contains only the event name, otherwise the given event object will be used to pass
     * to each event listener.
     * If the event is an event object then this event should provide the event name by declare
     * a public constant named NAME, which defined the unique name of the event.
     *
     * @param EventInterface|string $event
     */
    public function fire($event): void;
}