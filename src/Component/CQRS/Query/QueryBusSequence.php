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

namespace Vection\Component\CQRS\Query;

use Vection\Contracts\CQRS\Common\BusSequence;
use Vection\Contracts\CQRS\Common\MessageInterface;
use Vection\Contracts\CQRS\Query\QueryBusSequenceInterface;
use Vection\Contracts\CQRS\Query\ReadModelInterface;

/**
 * Class QueryBusSequence
 *
 * @package Vection\Component\CQRS\Query
 */
class QueryBusSequence extends BusSequence implements QueryBusSequenceInterface
{

    /**
     * Invokes the next middleware of this sequence.
     *
     * @param MessageInterface $message
     *
     * @return ReadModelInterface|null
     */
    public function invokeNext(MessageInterface $message): ?ReadModelInterface
    {
        $relay = $this->relay;

        if ( $middleware = $relay() ) {
            return $middleware($message, $this);
        }

        return null;
    }
}