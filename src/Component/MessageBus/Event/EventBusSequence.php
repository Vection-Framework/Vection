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

namespace Vection\Component\MessageBus\Event;

use Vection\Contracts\MessageBus\BusSequence;
use Vection\Contracts\MessageBus\MessageInterface;
use Vection\Contracts\MessageBus\Event\EventBusSequenceInterface;

/**
 * Class EventBusSequence
 *
 * @package Vection\Component\MessageBus\Event
 */
class EventBusSequence extends BusSequence implements EventBusSequenceInterface
{
    /**
     * @param MessageInterface $message
     */
    public function invokeNext(MessageInterface $message): void
    {
        $relay = $this->relay;

        if ( $middleware = $relay() ) {
            $middleware($message, $this);
        }
    }
}