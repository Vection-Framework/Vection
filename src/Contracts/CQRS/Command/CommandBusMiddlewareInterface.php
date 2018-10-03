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

/**
 * Interface CommandBusMiddlewareInterface
 *
 * @package Vection\Contracts\CQRS\Command
 */
interface CommandBusMiddlewareInterface
{
    /**
     * This method executes the middleware specific logic.
     *
     * @param CommandInterface $command
     * @param CommandBusSequenceInterface $sequence
     */
    public function __invoke(CommandInterface $command, CommandBusSequenceInterface $sequence);
}