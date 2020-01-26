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

use Vection\Component\Messenger\Message;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\MessageIdGenerator;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MessageRelationInterface;
use Vection\Contracts\Messenger\Service\Command\CommandBusInterface;

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
     * @var MessageIdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * CommandBus constructor.
     *
     * @param MessageBusInterface              $messageBus
     * @param MessageIdGeneratorInterface|null $idGenerator
     */
    public function __construct(MessageBusInterface $messageBus, MessageIdGeneratorInterface $idGenerator = null)
    {
        $this->messageBus  = $messageBus;
        $this->idGenerator = $idGenerator ?: new MessageIdGenerator();
    }

    /**
     * @inheritDoc
     */
    public function execute(object $command, ?MessageRelationInterface $relation = null): void
    {
        if (! $command instanceof MessageInterface) {
            $command = new Message($command);
        }

        if ( $relation ) {
            foreach ($relation->getHeaders()->toArray() as $name => $value) {
                $command = $command->withHeader($name, $value);
            }
        }

        $headers = $command->getHeaders();

        if (! $headers->has(MessageHeaders::MESSAGE_ID)) {
            $command = $command->withHeader(MessageHeaders::MESSAGE_ID, $this->idGenerator->generate());
        }

        if (! $headers->has(MessageHeaders::TIMESTAMP)) {
            $command = $command->withHeader(MessageHeaders::TIMESTAMP, (string) time());
        }

        $this->messageBus->dispatch(
            $command->withHeader(MessageHeaders::MESSAGE_TAG, 'command')
        );
    }

}
