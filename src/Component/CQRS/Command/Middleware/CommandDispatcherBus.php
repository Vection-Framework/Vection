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

namespace Vection\Component\CQRS\Command\Middleware;

use Vection\Contracts\CQRS\Command\CommandBusSequenceInterface;
use Vection\Contracts\CQRS\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;
use Vection\Contracts\CQRS\Command\CommandResolverInterface;

/**
 * Class CommandDispatcherBus
 *
 * @package Vection\Component\CQRS\Command\Middleware
 */
class CommandDispatcherBus implements CommandBusMiddlewareInterface
{
    /** @var CommandResolverInterface */
    protected $resolver;

    /**
     * DispatcherCommandBus constructor.
     * @param CommandResolverInterface $resolver
     */
    public function __construct(CommandResolverInterface $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param CommandInterface $command
     * @param CommandBusSequenceInterface $sequence
     */
    public function __invoke(CommandInterface $command, CommandBusSequenceInterface $sequence)
    {
        $handler = $this->resolver->resolve($command);

        $handler($command);

        $sequence->invokeNext($command);
    }
}