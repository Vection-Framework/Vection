<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
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
use Vection\Contracts\Messenger\ConditionalHandlerInterface;
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
    protected MessageHandlerProviderInterface $handlerMapper;

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
     * @inheritDoc
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $handler = $this->handlerMapper->getHandler($message);

        if ( $handler === null ) {
            throw new HandlerNotFoundException(
                sprintf(
                    'Missing handler mapping for message with body %s',
                    get_class($message->getBody())
                )
            );
        }

        try {
            if ($handler instanceof ConditionalHandlerInterface) {
                $handler->checkCondition();
            }

            $handler($message->getBody(), $message);
        }
        catch (Throwable $e) {
            throw new HandlerFailedException($message, $e);
        }

        $message = $message->withHeader(MessageHeaders::HANDLED_TIMESTAMP, (string) time());

        return $sequence->next($message);
    }
}
