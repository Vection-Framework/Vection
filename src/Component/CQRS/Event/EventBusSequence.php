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

namespace Vection\Component\CQRS\Event;

use Vection\Contracts\CQRS\Common\BusSequence;
use Vection\Contracts\CQRS\Common\MessageInterface;
use Vection\Contracts\CQRS\Event\EventBusSequenceInterface;

/**
 * Class EventBusSequence
 *
 * @package Vection\Component\CQRS\Event
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