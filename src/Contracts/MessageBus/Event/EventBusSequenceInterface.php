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

use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Interface EventBusSequenceInterface
 *
 * @package Vection\Contracts\MessageBus\Event
 */
interface EventBusSequenceInterface
{
    /**
     * @param MessageInterface $message
     */
    public function invokeNext(MessageInterface $message): void;

    /**
     * Checks if there is a next middleware object.
     *
     * @return bool
     */
    public function hasNext(): bool;
}