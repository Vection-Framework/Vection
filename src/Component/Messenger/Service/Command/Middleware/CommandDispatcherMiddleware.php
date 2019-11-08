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

declare(strict_types = 1);

namespace Vection\Component\Messenger\Service\Command\Middleware;

use InvalidArgumentException;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\Service\Command\CommandDispatcherInterface;
use Vection\Contracts\Messenger\Service\Command\CommandInterface;

/**
 * Class CommandDispatcherMiddleware
 *
 * @package Vection\Component\Messenger\Service\Command\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class CommandDispatcherMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @var CommandDispatcherInterface
     */
    protected $commandDispatcher;

    /**
     * CommandDispatcherMiddleware constructor.
     *
     * @param CommandDispatcherInterface $commandDispatcher
     */
    public function __construct(CommandDispatcherInterface $commandDispatcher)
    {
        $this->commandDispatcher = $commandDispatcher;
    }

    /**
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $command = $message->getPayload();

        if (! $command instanceof CommandInterface) {
            throw new InvalidArgumentException('Expects a message payload of type CommandInterface');
        }

        $this->commandDispatcher->dispatch($command);

        return $sequence->next($message);
    }
}