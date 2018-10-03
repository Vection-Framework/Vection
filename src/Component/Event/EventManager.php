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

namespace Vection\Component\Event;

use Vection\Contracts\Event\EventDispatcherInterface;
use Vection\Contracts\Event\EventInterface;

/**
 * Class EventManager
 *
 * @package Vection\Component\Event
 */
class EventManager implements EventDispatcherInterface
{
    /**
     * This property contains a callable that
     * creates a new handler object.
     *
     * @var callable
     */
    protected $factory;

    /**
     * Contains all registered event which are
     * mapped by the event class name to event name.
     *
     * @var array
     */
    protected $events = [];

    /**
     * Contains all registered event handler which are
     * mapped by the event name. One event can have multiple
     * event handlers.
     *
     * @var array[]
     */
    protected $handler = [];

    /**
     * Adds a set of event mapping that contains all event classes
     * which are mapped by event names.
     *
     * @param array $mapping
     */
    public function addEventMapping(array $mapping): void
    {
        $this->events = ! $this->events ? $mapping : array_merge($this->events, $mapping);
    }

    /**
     * Adds a set of handler mapping that contains all handler
     * mapped by event names.
     *
     * @param array $mapping
     */
    public function addHandlerMapping(array $mapping): void
    {
        $this->handler = ! $this->handler ? $mapping : array_merge($this->handler, $mapping);
    }

    /**
     * {@inheritdoc}
     */
    public function setHandlerFactoryCallback(callable $closure): void
    {
        $this->factory = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function addHandler(string $event, $handler, int $priority = 0): void
    {
        $this->handler[$event][] = [ $handler, $priority ];
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($event): void
    {
        if ( $event instanceof EventInterface ) {
            $eventClass = \get_class($event);

            # Try to get the event name by registered event by class name
            if ( ! $eventName = ( $this->events[$eventClass] ?? null ) ) {
                # There is no event registered by class name, so use a NAME const if exists or takes the class name
                $eventName = \defined("{$eventClass}::NAME") ? \constant("{$eventClass}::NAME") : $eventClass;
            }
        } else {
            $eventName = (string)$event;
            $event = new DefaultEvent($eventName);
        }

        if ( ! isset($this->handler[$eventName]) ) {
            # There is no handler registered for this event
            return;
        }

        $handlers = [];

        foreach ( $this->handler[$eventName] as $definition ) {
            # Saves the callable array as handler [xxx, method]
            $handler = $definition[0];

            # If the first element of the callable array is a string
            # then first create the handler object on which we will call the method
            if ( \is_array($handler) && \is_string($handler[0]) ) {
                $className = $handler[0];

                if ( ! \class_exists($className) ) {
                    throw new \InvalidArgumentException(
                        'EventManager::createHandler expect an existing fqn of handler class, got "' . $className . '"'
                    );
                }

                if ( $this->factory ) {
                    $handler[0] = ( $this->factory )($className);
                } else {
                    try {
                        $handler[0] = new $className();
                    } catch ( \Exception $e ) {
                        throw new \RuntimeException('Error when try to create event handler.', 0, $e);
                    }
                }

            }

            # Saves the callable array by considering the priority
            $handlers[$definition[1] ?? 0][] = $handler;
        }

        for ( $i = \count($handlers) - 1; $i >= 0; $i-- ) {
            foreach ( $handlers[$i] as $handler ) {
                if ( $event->isPropagationStopped() ) {
                    return;
                }
                call_user_func($handler, $event, $this);
            }
        }
    }
}