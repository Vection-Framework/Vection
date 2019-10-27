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

declare(strict_types=1);

/*
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Command\Middleware;

use Vection\Contracts\MessageBus\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Command\CommandBusSequenceInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\Command\CommandResolverInterface;

/**
 * Class CommandDispatcherBus
 *
 * @package Vection\Component\MessageBus\Command\Middleware
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
        // @var callable $handler
        $handler = $this->resolver->resolve($command);

        $handler($command);

        $sequence->invokeNext($command);
    }
}
