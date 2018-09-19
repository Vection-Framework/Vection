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

namespace Vection\Contracts\CQRS\Command;

/**
 * Interface CommandResolverInterface
 *
 * @package Vection\Contracts\CQRS\Command
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