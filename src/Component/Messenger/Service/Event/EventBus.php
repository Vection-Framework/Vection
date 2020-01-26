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

namespace Vection\Component\Messenger\Service\Event;

use Vection\Component\Messenger\Message;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\MessageIdGenerator;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MessageRelationInterface;
use Vection\Contracts\Messenger\Service\Event\EventBusInterface;

/**
 * Class EventBus
 *
 * @package Vection\Component\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventBus implements EventBusInterface
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
     * EventBus constructor.
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
    public function publish(object $event, ?MessageRelationInterface $relation = null): void
    {
        if (! $event instanceof MessageInterface) {
            $event = new Message($event);
        }

        if( $relation ){
            foreach($relation->getHeaders()->toArray() as $name => $value) {
                $event = $event->withHeader($name, $value);
            }
        }

        $headers = $event->getHeaders();

        if (! $headers->has(MessageHeaders::MESSAGE_ID)) {
            $event = $event->withHeader(MessageHeaders::MESSAGE_ID, $this->idGenerator->generate());
        }

        if (! $headers->has(MessageHeaders::TIMESTAMP)) {
            $event = $event->withHeader(MessageHeaders::TIMESTAMP, (string) time());
        }

        $this->messageBus->dispatch(
            $event->withHeader(MessageHeaders::MESSAGE_TAG, 'event')
        );
    }
}
