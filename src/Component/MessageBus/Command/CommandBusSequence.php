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

namespace Vection\Component\MessageBus\Command;

use Vection\Contracts\MessageBus\BusSequence;
use Vection\Contracts\MessageBus\Command\CommandBusSequenceInterface;
use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Class CommandBusSequence
 *
 * @package Vection\Component\MessageBus\Command
 */
class CommandBusSequence extends BusSequence implements CommandBusSequenceInterface
{
    /**
     * Invokes the next middleware of this sequence.
     *
     * @param MessageInterface $message
     */
    public function invokeNext(MessageInterface $message)
    {
        $relay = $this->relay;

        if ( $middleware = $relay() ) {
            $middleware($message, $this);
        }
    }
}
