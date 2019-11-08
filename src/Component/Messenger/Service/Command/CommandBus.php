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

namespace Vection\Component\Messenger\Service\Command;

use Vection\Component\Messenger\MessageBuilder;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\Service\Command\CommandBusInterface;
use Vection\Contracts\Messenger\Service\Command\CommandInterface;

/**
 * Class CommandBus
 *
 * @package Vection\Component\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
class CommandBus implements CommandBusInterface
{
    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * CommandBus constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param CommandInterface $command
     */
    public function exec(CommandInterface $command): void
    {
        $message = (new MessageBuilder())->withPayload($command)->build();
        $this->messageBus->dispatch($message);
    }
}