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

use Error;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceFactoryInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;

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
        $this->middlewareSequenceFactory = ($middlewareSequenceFactory ?: new MiddlewareSequenceFactory());
        $this->logger = new NullLogger();
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
     * @throws MessageBusException
     */
    public function dispatch(MessageInterface $message): MessageInterface
    {
        $payload = $message->getPayload();
        $headers = $message->getHeaders();

        $this->logger->info(sprintf(
            "[MessageBus] Start dispatching message with payload (%s%s) with headers:\n%s",
            gettype($payload),
            $payload !== null && is_object($payload) ? '@'.get_class($payload) : '',
            print_r($headers->toArray(), true)
        ));

        $sequence = $this->middlewareSequenceFactory->create($this->middleware);
        $result = $this->executeMiddleware($message, $sequence);

        $this->logger->info(sprintf(
            '[MessageBus] Message with payload (%s%s) has been dispatched.',
            gettype($payload),
            $payload !== null && is_object($payload) ? '@'.get_class($payload) : ''
        ));

        return $result;
    }

    /**
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     *
     * @throws MessageBusException
     */
    protected function executeMiddleware(
        MessageInterface $message, MiddlewareSequenceInterface $sequence
    ): MessageInterface
    {
        try {
            return $sequence->next($message);
        }
        catch (Exception | Error $e) {

            if ($e instanceof MessageBusException) {
                # Interrupt the middleware handling only if there is an hart error
                # thrown by an exception of type MessageBusException

                $this->logger->error(sprintf(
                    "[MessageBus] %s\nMessenger: %s\n%s\n%s",
                    'Exception: An error has caused an interruption of the further middleware handling.',
                    $message->getHeaders()->getId(),
                    $e->getMessage(),
                    $e->getTraceAsString()
                ));

                throw $e;
            }

            $this->logger->error(sprintf(
                '[MessageBus] Exception: An error occurred while handling by middleware %s. '
                ."Continue with next middleware.\nMessenger: %s\n%s\n%s",
                get_class($sequence->getCurrent()),
                $message->getHeaders()->getId(),
                $e->getMessage(),
                $e->getTraceAsString()
            ));

            # Execute next middleware due a soft exception should not interrupt other middleware
            return $this->executeMiddleware($message, $sequence);
        }
    }
}