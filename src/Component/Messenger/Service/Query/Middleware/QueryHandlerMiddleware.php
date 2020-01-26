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

namespace Vection\Component\Messenger\Service\Query\Middleware;

use Throwable;
use Vection\Component\Messenger\Exception\HandlerFailedException;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\Middleware\HandlerMiddleware;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;

/**
 * Class QueryHandlerMiddleware
 *
 * @package Vection\Component\Messenger\Service\Query\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class QueryHandlerMiddleware extends HandlerMiddleware
{
    /**
     * @inheritDoc
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        try {
            $handler = $this->handlerMapper->getHandler($message);
            $body    = $handler($message->getBody(), $message);
            return $message
                ->withHeader(MessageHeaders::HANDLED_TIMESTAMP, (string) time())
                ->withHeader(MessageHeaders::TERMINATED_MIDDLEWARE, 'QueryHandlerMiddleware')
                ->withBody($body);
        } catch (Throwable $e) {
            throw new HandlerFailedException($e);
        }
    }
}
