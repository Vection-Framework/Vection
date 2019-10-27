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
 * Interface CommandResolverInterface
 *
 * @package Vection\Contracts\MessageBus\Command
 */
interface CommandResolverInterface
{

    /**
     * Returns a command handler related to the given
     * command object.
     *
     * @param CommandInterface $command
     *
     * @return CommandHandlerInterface
     */
    public function resolve(CommandInterface $command): CommandHandlerInterface;
}
