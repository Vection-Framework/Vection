<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Event\Middleware;

use Vection\Contracts\CQRS\Event\EventBusMiddlewareInterface;
use Vection\Contracts\CQRS\Event\EventBusSequenceInterface;
use Vection\Contracts\CQRS\Event\EventInterface;
use Vection\Contracts\Event\EventDispatcherInterface;

/**
 * Class EventPublisherBus
 *
 * @package Vection\Component\CQRS\Event\Middleware
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