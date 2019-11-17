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

declare(strict_types=1);

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Event;

use Exception;
use InvalidArgumentException;
use Psr\EventDispatcher\StoppableEventInterface;
use RuntimeException;
use Vection\Contracts\Event\EventInterface;
use Vection\Contracts\Event\EventListenerInterface;
use Vection\Contracts\Event\EventManagerInterface;

/**
 * Class EventManager
 *
 * @package Vection\Component\Event
 */
class EventManager implements EventManagerInterface
{

    /**
     * This property contains a callable that
     * creates a new listener object.
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
     * Contains all registered event listeners which are
     * mapped by the event name. One event can have multiple
     * event listeners.
     *
     * @var array[]
     */
    protected $listeners = [];

    /**
     * If this property is set, the this event manager will supports
     * the notification of listeners by event wildcard matching. The
     * wildcard separator will be used to limit the matching.
     *
     * @var string
     */
    protected $eventWildcardSeparator;

    /**
     * This method is an alternative way to register event classes
     * instead using the EventManager::addEventListener method.
     * Adds an array that contains all event names mapped by event FQCNs.
     *
     * E.g. [.., FQCN => eventName, ..]
     *
     * @param array $mapping
     */
    public function setEventClassMap(array $mapping): void
    {
        $this->events = $mapping;
    }

    /**
     * This method is an alternative way to register event listeners
     * instead using the EventManager::addEventListener method.
     * Add an array that contains all listener data mapped by event names.
     *
     * E.g. [.., eventName => [.., [handler<callable>, priority<int>], ..]
     *
     * @param array $mapping
     */
    public function setEventListenerMap(array $mapping): void
    {
        $this->listeners = $mapping;
    }

    /**
     * @inheritdoc
     */
    public function setEventListenerFactory(callable $closure): void
    {
        $this->factory = $closure;
    }

    /**
     * @inheritdoc
     */
    public function addEventListener(string $event, $listener, int $priority = 0): void
    {
        $this->listeners[$event][] = [ $listener, $priority ];
    }

    /**
     * @inheritdoc
     */
    public function setWildcardSeparator(string $separator): void
    {
        if ( ! in_array($separator, ['.', ':', '-', '/']) ) {
            throw new InvalidArgumentException(
                'The event name wildcard separator support only ".:-/" characters.'
            );
        }
        $this->eventWildcardSeparator = $separator;
    }

    /**
     * @inheritdoc
     */
    public function fire($event): void
    {
        if ( $event instanceof EventInterface ) {
            # We have already the event object, now we need the event name
            $eventClass = get_class($event);

            # Try to get the event name by registered event by class name
            if ( ! $eventName = ( $this->events[$eventClass] ?? null ) ) {
                # There is no event registered by class name, so use a NAME const if exists or takes the class name
                $eventName = defined("{$eventClass}::NAME") ? constant("{$eventClass}::NAME") : $eventClass;
            }
        } else {
            $eventName = (string) $event;
            $event     = new DefaultEvent($eventName);
        }

        # Matched listeners that will be notified
        $prioritizedListeners = [];
        $registeredListeners  = [];

        if ( ! $this->eventWildcardSeparator ) {
            $registeredListeners = ($this->listeners[$eventName] ?? []);
        } else {
            # We have to compare the event parts by wildcard separator
            $eventNameParts = explode($this->eventWildcardSeparator, $eventName);

            # Filter all listeners which matching the fired event by comparing the event parts
            foreach ( $this->listeners as $keyEventName => $eventListeners ) {
                $keyEventNameParts = explode($this->eventWildcardSeparator, $keyEventName);

                # The section count from fired event must be at least
                # equals or higher then the one of listeners registered event name sections
                if ( count($keyEventNameParts) > count($eventNameParts) ) {
                    continue;
                }

                # This listener wil only be notified if all section matching against the fired event
                foreach ($keyEventNameParts as $i => $part) {
                    if ( $part !== $eventNameParts[$i] ) {
                        continue 2;
                    }
                }

                foreach ( $eventListeners as $eventListener ) {
                    $registeredListeners[] = $eventListener;
                }
            }
        }

        if ( ! $registeredListeners ) {
            # There is no listener registered for this event
            return;
        }

        foreach ( array_values($registeredListeners) as $definition ) {
            # Saves the callable array as handler [xxx, method]
            $handler = $definition[0];

            # If the first element of the callable array is a string
            # then first create the listener object on which we will call the method
            if ( is_array($handler) && is_string($handler[0]) ) {
                $listenerClassName = $handler[0];

                if ( ! class_exists($listenerClassName) ) {
                    throw new InvalidArgumentException(
                        'Vection.EventManager: The creation of the listener object expect an existing FQCN, got "' . $listenerClassName . '"'
                    );
                }

                if ( $this->factory ) {
                    $handler[0] = ( $this->factory )($listenerClassName);
                } else {
                    try {
                        $handler[0] = new $listenerClassName();
                    } catch ( Exception $e ) {
                        throw new RuntimeException('Error when try to create event listener.', 0, $e);
                    }
                }

            }

            # Saves the callable array by considering the priority
            if ($handler[0] instanceof EventListenerInterface) {
                $prioritizedListeners[$handler[0]->getPriority()][] = $handler;
            } else {
                $prioritizedListeners[($definition[1] ?? 0)][] = $handler;
            }
        }

        # Sort by priority
        ksort($prioritizedListeners);

        # Start with highest priority x > 0
        foreach (array_reverse($prioritizedListeners) as $listeners) {
            foreach ($listeners as $listener) {

                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    return;
                }

                if ($listener[0] instanceof EventListenerInterface) {
                    # This listener provides a method to determine the handler method
                    # instead of using the method section of the annotation
                    $listener[1] = $listener[0]->getHandlerMethodName();
                }

                $listener($event, $this);
            }
        }
    }
}
