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

namespace Vection\Contracts\CQRS\Event;

use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Interface EventBusSequenceInterface
 *
 * @package Vection\Contracts\CQRS\Event
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