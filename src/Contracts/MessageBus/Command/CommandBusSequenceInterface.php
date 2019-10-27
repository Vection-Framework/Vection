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

namespace Vection\Contracts\MessageBus\Command;

use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Interface CommandBusSequenceInterface
 *
 * @package Vection\Contracts\MessageBus\Command
 */
interface CommandBusSequenceInterface
{
    /**
     * Invokes the next middleware of this sequence.
     *
     * @param MessageInterface $message
     */
    public function invokeNext(MessageInterface $message);

    /**
     * Checks if there is a next middleware object.
     *
     * @return bool
     */
    public function hasNext(): bool;
}
