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

use Vection\Contracts\CQRS\Command\CommandHandlerFactoryInterface;
use Vection\Contracts\CQRS\Command\CommandHandlerInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;
use Vection\Contracts\CQRS\Command\CommandResolverInterface;

/**
 * Class CommandResolver
 *
 * @package Vection\Component\CQRS\Command
 */
class CommandResolver implements CommandResolverInterface
{
    /** @var CommandHandlerFactoryInterface */
    protected $factory;

    /** @var string[] */
    protected $commandHandlerMap;

    /**
     * CommandResolver constructor.
     *
     * @param null|CommandHandlerFactoryInterface $factory
     * @param array                               $commandHandlerMap
     */
    public function __construct(? CommandHandlerFactoryInterface $factory = null, array $commandHandlerMap = [])
    {
        $this->factory = $factory;
        $this->commandHandlerMap = $commandHandlerMap;
    }

    /**
     * @param string $commandFQCN
     * @param string $handlerFQCN
     */
    public function register(string $commandFQCN, string $handlerFQCN)
    {
        $this->commandHandlerMap[$commandFQCN] = $handlerFQCN;
    }

    /**
     * @inheritdoc
     */
    public function resolve(CommandInterface $command): CommandHandlerInterface
    {
        $className = $this->commandHandlerMap[get_class($command)] ?? null;

        if ( ! $className ) {
            throw new \RuntimeException(
                'There is no handler registered for command "' . get_class($command) . '"'
            );
        }

        if ( ! array_key_exists(CommandHandlerInterface::class, class_implements($className)) ) {
            throw new \RuntimeException(
                'Invalid command handler mapping (type mismatch) for command "' . get_class($command) . '"'
            );
        }

        return $this->factory ? $this->factory->create($className) : new $className();
    }
}