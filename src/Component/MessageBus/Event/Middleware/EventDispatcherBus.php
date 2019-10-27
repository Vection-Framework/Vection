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

namespace Vection\Component\MessageBus\Event\Middleware;

use Vection\Contracts\Event\EventManagerInterface;
use Vection\Contracts\MessageBus\Event\EventBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Event\EventBusSequenceInterface;
use Vection\Contracts\MessageBus\Event\EventInterface;

/**
 * Class EventPublisherBus
 *
 * @package Vection\Component\MessageBus\Event\Middleware
 */
class EventDispatcherBus implements EventBusMiddlewareInterface
{

    /** @var EventManagerInterface */
    protected $dispatcher;

    /**
     * DispatcherEventBus constructor.
     *
     * @param EventManagerInterface $dispatcher
     */
    public function __construct(EventManagerInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param EventInterface            $event
     * @param EventBusSequenceInterface $sequence
     */
    public function __invoke(EventInterface $event, EventBusSequenceInterface $sequence)
    {
        $this->dispatcher->fire($event);
        $sequence->invokeNext($event);
    }
}
