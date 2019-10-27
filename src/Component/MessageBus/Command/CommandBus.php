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

namespace Vection\Component\MessageBus\Command;

use Vection\Contracts\MessageBus\Command\CommandBusInterface;
use Vection\Contracts\MessageBus\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;

/**
 * Class CommandBus
 *
 * @package Vection\Component\MessageBus\Command
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
