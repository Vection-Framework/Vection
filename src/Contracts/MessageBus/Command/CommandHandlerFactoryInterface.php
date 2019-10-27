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
 * Interface CommandHandlerFactoryInterface
 *
 * @package Vection\Contracts\MessageBus\Command
 */
interface CommandHandlerFactoryInterface
{
    /**
     * Creates a new instance of CommandHandlerInterface.
     *
     * @param string $class
     *
     * @return CommandHandlerInterface
     */
    public function create(string $class): CommandHandlerInterface;
}
