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
    protected array $listeners = [];
    protected EventListenerFactoryInterface|null $eventListenerFactory;

    /**
     * @var callable|null
     */
    protected $lazyListenerLoader = null;

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
     * Loads and registers all listeners just before the first listener
     * is requested by the event dispatcher.
     *
     * @param callable $fn
     */
    public function onLazyLoad(callable $fn): void
    {
        $this->lazyListenerLoader = $fn;
    }

    /**
     * @param string $event
     * @param string $listener
     * @param string $method
     */
    public function register(string $event, string $listener, string $method = '__invoke'): void
    {
        if (! isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = [
            'class' => $listener,
            'method' => $method
        ];
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

        if ($this->lazyListenerLoader !== null && count($this->listeners) === 0) {
            ($this->lazyListenerLoader)($this);
        }

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
