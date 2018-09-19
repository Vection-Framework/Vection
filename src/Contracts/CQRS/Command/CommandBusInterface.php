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
 * Interface CommandBusInterface
 *
 * @package Vection\Contracts\CQRS\Command
 */
interface CommandBusInterface
{
    /**
     * Attaches a middleware to this bus.
     *
     * @param CommandBusMiddlewareInterface $middleware
     */
    public function attach(CommandBusMiddlewareInterface $middleware): void;

    /**
     * Handle the execution of all attached middleware in order
     * they were attached to this bus.
     *
     * @param CommandInterface $message
     *
     */
    public function execute(CommandInterface $message): void;

    /**
     * Alias for the execute method.
     *
     * @param CommandInterface $message
     *
     * @see CommandBusInterface::execute()
     */
    public function __invoke(CommandInterface $message): void;
}