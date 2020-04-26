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

use Vection\Contracts\Event\EventDispatcherInterface;
use Vection\Contracts\Event\EventInterface;
use Vection\Contracts\Event\EventListenerProviderInterface;

/**
 * Class EventDispatcher
 *
 * @package Vection\Component\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventListenerProviderInterface
     */
    protected $listenerProvider;

    /**
     * EventDispatcher constructor.
     *
     * @param EventListenerProviderInterface $listenerProvider
     */
    public function __construct(EventListenerProviderInterface $listenerProvider)
    {
        $this->listenerProvider = $listenerProvider;
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event): object
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);

        foreach ($listeners as $listener) {
            if ($event instanceof EventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event);
        }

        return $event;
    }

}
