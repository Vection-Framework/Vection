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

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Throwable;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceFactoryInterface;

/**
 * Class MessageBus
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageBus implements MessageBusInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var MessageBusMiddlewareInterface[]
     */
    protected $middleware;

    /**
     * @var MiddlewareSequenceFactoryInterface
     */
    protected $middlewareSequenceFactory;

    /**
     * MessageBus constructor.
     *
     * @param MiddlewareSequenceFactoryInterface|null $middlewareSequenceFactory
     */
    public function __construct(MiddlewareSequenceFactoryInterface $middlewareSequenceFactory = null)
    {
        $this->logger = new NullLogger();
        $this->middlewareSequenceFactory = ($middlewareSequenceFactory ?: new MiddlewareSequenceFactory());
    }

    /**
     * @param MessageBusMiddlewareInterface $middleware
     */
    public function addMiddleware(MessageBusMiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @param MessageInterface $message
     *
     * @return MessageInterface
     *
     * @throws Throwable
     */
    public function dispatch(MessageInterface $message): MessageInterface
    {
        $headers = $message->getHeaders();
        $body    = str_replace('\\', '.', get_class($message->getBody()));

        $this->logger->info(
            sprintf(
                '[MessageBus] DISPATCH %s (ID:%s)',
                $body,
                $headers->get(MessageHeaders::MESSAGE_ID) ?: '[none]'
            )
        );

        $sequence = $this->middlewareSequenceFactory->create($this->middleware);

        try {
            $result = $sequence->next($message);

            $this->logger->info(
                sprintf(
                    '[MessageBus] DONE %s (ID:%s) TERMINATED BY %s',
                    $body,
                    $headers->get(MessageHeaders::MESSAGE_ID) ?: '[none]',
                    $result->getHeaders()->get(MessageHeaders::TERMINATED_MIDDLEWARE) ?: 'unknown'
                )
            );

            return $result;
        } catch (Throwable $e) {

            $this->logger->error(
                sprintf(
                    "[MessageBus] ERROR %s (ID:%s)\n%s at %s\n%s\n%s",
                    $body,
                    $message->getHeaders()->get(MessageHeaders::MESSAGE_ID) ?: '[none]',
                    (new \ReflectionObject($e))->getShortName(),
                    str_replace('\\', '.', get_class($sequence->getCurrent())),
                    $e->getMessage(),
                    $e->getTraceAsString()
                )
            );

            throw  $e;
        }
    }
}
