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

/**
 * Interface CommandBusMiddlewareInterface
 *
 * @package Vection\Contracts\MessageBus\Command
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
