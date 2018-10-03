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

namespace Vection\Contracts\CQRS\Query;

/**
 * Interface QueryBusMiddlewareInterface
 *
 * @package Vection\Contracts\CQRS\Command
 */
interface QueryBusMiddlewareInterface
{
    /**
     * This method executes the middleware specific logic.
     *
     * @param QueryInterface $message
     * @param QueryBusSequenceInterface $sequence
     *
     * @return ReadModelInterface|null
     */
    public function __invoke(QueryInterface $message, QueryBusSequenceInterface $sequence);
}