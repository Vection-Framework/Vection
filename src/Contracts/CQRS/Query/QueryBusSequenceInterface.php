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

namespace Vection\Contracts\CQRS\Query;

use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Class QueryBusSequence
 *
 * @package Vection\Contracts\CQRS\Query
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