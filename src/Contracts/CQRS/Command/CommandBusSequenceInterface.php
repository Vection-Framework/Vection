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

namespace Vection\Contracts\CQRS\Command;

use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Interface CommandBusSequenceInterface
 *
 * @package Vection\Contracts\CQRS\Command
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