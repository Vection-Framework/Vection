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

namespace Vection\Component\Messenger\Middleware;

use Vection\Component\Messenger\Exception\TransportException;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\Transport\SenderInterface;

/**
 * Class SenderMiddleware
 *
 * @package Vection\Component\Messenger\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class SenderMiddleware implements MessageBusMiddlewareInterface
{
    protected SenderInterface $sender;

    /**
     * MessageSenderMiddleware constructor.
     *
     * @param SenderInterface $sender
     */
    public function __construct(SenderInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     *
     * @throws TransportException
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        if ( ! $message->getHeaders()->has(MessageHeaders::RECEIVED_TIMESTAMP) ) {

            if (! $message->getHeaders()->has(MessageHeaders::MESSAGE_TYPE)) {
                $message = $message->withHeader(MessageHeaders::MESSAGE_TYPE, get_class($message->getBody()));
            }

            if ($message->getHeaders()->has(MessageHeaders::DELIVERY_TIMESTAMP)) {
                $message = $message->withHeader(MessageHeaders::REDELIVERED_TIMESTAMP, (string) time());
            } else {
                $message = $message->withHeader(MessageHeaders::DELIVERY_TIMESTAMP, (string) time());
            }

            $this->sender->send($message);
        }

        return $sequence->next($message);
    }
}
