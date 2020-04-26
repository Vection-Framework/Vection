<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Event;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Event\Exception\InvalidEventListenerException;
use Vection\Component\Event\Exception\RuntimeException;
use Vection\Contracts\Event\EventListenerFactoryInterface;
use Vection\Contracts\Event\EventListenerProviderInterface;

/**
 * Class EventListenerProvider
 *
 * @package Vection\Component\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventListenerProvider implements EventListenerProviderInterface
{
    /**
     * @var array
     */
    protected $listeners;

    /**
     * @var EventListenerFactoryInterface|null
     */
    protected $eventListenerFactory;

    /**
     * EventListenerProvider constructor.
     *
     * @param EventListenerFactoryInterface|null $eventListenerFactory
     */
    public function __construct(?EventListenerFactoryInterface $eventListenerFactory = null)
    {
        $this->eventListenerFactory = $eventListenerFactory;
    }

    /**
     * Registers the given class to one or more events.
     * The events will be get from existing "on" methods with typed event parameter.
     *
     * @param string $className
     */
    public function register(string $className): void
    {
        try {
            $methods       = (new ReflectionClass($className))->getMethods(ReflectionMethod::IS_PUBLIC);
            $validListener = false;

            foreach ($methods as $method) {
                if (strpos($method->getName(), 'on') !== 0) {
                    continue;
                }

                $parameters = $method->getParameters();

                if (count($parameters) === 0) {
                    continue;
                }

                $type = $parameters[0]->getType();

                if ($type === null) {
                    continue;
                }

                $eventClassName = $type->getName();

                $eventName = explode('\\', $eventClassName)[substr_count($eventClassName, '\\')];

                if ($method->getName() !== 'on'.$eventName) {
                    continue;
                }

                $validListener = true;

                if (! isset($this->listeners[$eventClassName])) {
                    $this->listeners[$eventClassName] = [];
                }

                $this->listeners[$eventClassName][] = [
                    'class' => $className, 'method' => $method->getName(),
                ];
            }

            if (! $validListener) {
                throw new InvalidEventListenerException(
                    sprintf(
                        'Cannot register class %s. A listener must have at least one handler method with typed event.',
                        $className
                    )
                );
            }
        } catch (ReflectionException $e) {
            throw new RuntimeException('Unable to register unknown listener class.', 0, $e);
        }
    }

    /**
     * @param object $event
     *   An event for which to return the relevant listeners.
     *
     * @return iterable[callable]
     *   An iterable (array, iterator, or generator) of callables.  Each
     *   callable MUST be type-compatible with $event.
     */
    public function getListenersForEvent(object $event): iterable
    {
        $listeners = [];

        if (isset($this->listeners[get_class($event)])) {
            foreach ($this->listeners[get_class($event)] as $listener) {
                if ($this->eventListenerFactory !== null) {
                    $object = $this->eventListenerFactory->create($listener['class']);
                } else {
                    $object = new $listener['class'];
                }
                $listeners[] = [$object, $listener['method']];
            }
        }

        return $listeners;
    }

}
