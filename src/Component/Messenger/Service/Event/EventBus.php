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

use Vection\Component\Messenger\MessageBuilder;
use Vection\Contracts\Messenger\MessageBusInterface;
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
     * EventBus constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param mixed $event
     */
    public function publish($event): void
    {
        $message = (new MessageBuilder())->withPayload($event)->build();

        $this->messageBus->dispatch($message);
    }
}
