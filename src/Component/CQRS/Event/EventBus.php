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

namespace Vection\Component\CQRS\Event;

use Vection\Contracts\CQRS\Event\EventBusInterface;
use Vection\Contracts\CQRS\Event\EventBusMiddlewareInterface;
use Vection\Contracts\CQRS\Event\EventInterface;

/**
 * Class EventBus
 *
 * @package Vection\Component\CQRS\Event
 */
class EventBus implements EventBusInterface
{
    /**
     * This property contains all attached middleware buses.
     *
     * @var EventBusMiddlewareInterface[]
     */
    protected $middleware;

    /**
     * @inheritdoc
     */
    public function attach(EventBusMiddlewareInterface $eventBusMiddleware): void
    {
        $this->middleware[] = $eventBusMiddleware;
    }

    /**
     * @inheritdoc
     */
    public function publish(EventInterface $event): void
    {
        $sequence = new EventBusSequence($this->middleware);
        $sequence->invokeNext($event);
    }

    /**
     * @inheritdoc
     */
    public function __invoke(EventInterface $event): void
    {
        $this->publish($event);
    }
}