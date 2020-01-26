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

namespace Vection\Component\Messenger\Middleware;

use Throwable;
use Vection\Component\Messenger\Exception\HandlerFailedException;
use Vection\Component\Messenger\Exception\HandlerNotFoundException;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageHandlerProviderInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;

/**
 * Class MessageHandlerMiddleware
 *
 * @package Vection\Component\Messenger\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class HandlerMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @var MessageHandlerProviderInterface
     */
    protected $handlerMapper;

    /**
     * CommandHandlerMiddleware constructor.
     *
     * @param MessageHandlerProviderInterface $handlerMapper
     */
    public function __construct(MessageHandlerProviderInterface $handlerMapper)
    {
        $this->handlerMapper = $handlerMapper;
    }

    /**
     * Handles the message.
     * After this middleware is finished with handling, it must call
     * the next middleware by the sequence object and return its result.
     *
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     * @throws HandlerFailedException
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        try {
            $handler = $this->handlerMapper->getHandler($message);

            if ( $handler === null ) {
                throw new HandlerNotFoundException(
                    sprintf(
                        'Missing handler mapping for message with body %s',
                        get_class($message->getBody())
                    )
                );
            }

            $handler($message->getBody(), $message);
            $message = $message->withHeader(MessageHeaders::HANDLED_TIMESTAMP, (string) time());
        } catch (Throwable $e) {
            throw new HandlerFailedException($e);
        }

        return $sequence->next($message);
    }
}
