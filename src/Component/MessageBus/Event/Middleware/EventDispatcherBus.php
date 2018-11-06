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

namespace Vection\Component\MessageBus\Event\Middleware;

use Vection\Contracts\MessageBus\Event\EventBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Event\EventBusSequenceInterface;
use Vection\Contracts\MessageBus\Event\EventInterface;
use Vection\Contracts\Event\EventDispatcherInterface;

/**
 * Class EventPublisherBus
 *
 * @package Vection\Component\MessageBus\Event\Middleware
 */
class EventDispatcherBus implements EventBusMiddlewareInterface
{
    /** @var EventDispatcherInterface */
    protected $dispatcher;

    /**
     * DispatcherEventBus constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param EventInterface            $event
     * @param EventBusSequenceInterface $sequence
     */
    public function __invoke(EventInterface $event, EventBusSequenceInterface $sequence)
    {
        $this->dispatcher->dispatch($event);
        $sequence->invokeNext($event);
    }
}