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

namespace Vection\Component\CQRS\Command;

use Vection\Contracts\CQRS\Command\CommandBusInterface;
use Vection\Contracts\CQRS\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;

/**
 * Class CommandBus
 *
 * @package Vection\Component\CQRS\Command
 */
class CommandBus implements CommandBusInterface
{
    /**
     * This property contains all attached middleware buses.
     *
     * @var CommandBusMiddlewareInterface[]
     */
    protected $middleware;

    /**
     * @inheritdoc
     */
    public function __invoke(CommandInterface $message): void
    {
        $this->execute($message);
    }

    /**
     * @inheritdoc
     */
    public function attach(CommandBusMiddlewareInterface $commandBusMiddleware): void
    {
        $this->middleware[] = $commandBusMiddleware;
    }

    /**
     * @inheritdoc
     */
    public function execute(CommandInterface $message): void
    {
        $sequence = new CommandBusSequence($this->middleware);
        $sequence->invokeNext($message);
    }
}