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

namespace Vection\Contracts\MessageBus\Event;

/**
 * Interface EventBusMiddlewareInterface
 *
 * @package Vection\Contracts\MessageBus\Event
 */
interface EventBusMiddlewareInterface
{
    /**
     * This method executes the middleware specific logic.
     *
     * @param EventInterface $event
     * @param EventBusSequenceInterface $sequence
     */
    public function __invoke(EventInterface $event, EventBusSequenceInterface $sequence);
}