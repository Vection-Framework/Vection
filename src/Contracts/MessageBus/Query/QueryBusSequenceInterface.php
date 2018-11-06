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

namespace Vection\Contracts\MessageBus\Query;

use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Class QueryBusSequence
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryBusSequenceInterface
{
    /**
     * Invokes the next middleware of this sequence.
     *
     * @param MessageInterface $message
     *
     * @return ReadModelInterface|null
     */
    public function invokeNext(MessageInterface $message): ? ReadModelInterface;

    /**
     * Checks if there is a next middleware object.
     *
     * @return bool
     */
    public function hasNext(): bool;
}