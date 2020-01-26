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

namespace Vection\Component\Messenger;

use Vection\Contracts\Messenger\MessageHeadersInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MessageRelationInterface;

/**
 * Class MessageRelation
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageRelation implements MessageRelationInterface
{

    /**
     * @var string
     */
    protected $correlationId;

    /**
     * @var string
     */
    protected $causationId;

    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @inheritDoc
     */
    public function inCorrelation(string $id): MessageRelationInterface
    {
        $this->correlationId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function causedBy(string $id): MessageRelationInterface
    {
        $this->causationId = $id;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function with(MessageInterface $message): MessageRelationInterface
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): MessageHeadersInterface
    {
        $headers = [
            MessageHeaders::CORRELATION_ID => $this->correlationId,
            MessageHeaders::CAUSATION_ID => $this->causationId
        ];

        if ( $this->message ) {
            $messageHeaders = $this->message->getHeaders();

            if ($messageHeaders->has(MessageHeaders::CORRELATION_ID)) {
                $headers[MessageHeaders::CORRELATION_ID] = $messageHeaders->get(MessageHeaders::CORRELATION_ID);
            }

            if ($messageHeaders->has(MessageHeaders::MESSAGE_ID)) {
                $headers[MessageHeaders::CAUSATION_ID] = $messageHeaders->get(MessageHeaders::MESSAGE_ID);
            }
        }

        return new MessageHeaders(array_filter($headers));
    }
}
