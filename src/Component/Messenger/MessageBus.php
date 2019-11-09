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
use Throwable;
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
     * @throws Throwable
     */
    public function dispatch(MessageInterface $message): MessageInterface
    {
        $payload = $message->getPayload();
        $headers = $message->getHeaders();

        $this->logger->info(sprintf(
            '[MessageBus] DISPATCH %s (%s%s)',
            $headers->getId(),
            gettype($payload),
            $payload !== null && is_object($payload) ? '@'.get_class($payload) : ''
        ));

        $sequence = $this->middlewareSequenceFactory->create($this->middleware);
        $result = $this->executeMiddleware($message, $sequence);

        $this->logger->info(sprintf(
            '[MessageBus] FINISH %s (%s%s)',
            $headers->getId(),
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
     * @throws Throwable
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
                    "[MessageBus] ABORT %s\n%s\n%s\n%s",
                    $message->getHeaders()->getId(),
                    'An exception/error has caused an interruption of the further middleware execution.',
                    $e->getMessage(),
                    $e->getTraceAsString()
                ));

                $preException = $e->getPrevious();

                if ($preException) {
                    throw $preException;
                }

                throw $e;
            }

            $this->logger->error(sprintf(
                "[MessageBus] CATCH %s\nAn exception/error occurred at middleware %s.\n%s\n%s",
                $message->getHeaders()->getId(),
                get_class($sequence->getCurrent()),
                $e->getMessage(),
                $e->getTraceAsString()
            ));

            $this->logger->info('[MessageBus] CONTINUE Last error does not interrupt further execution.');

            # Execute next middleware due a soft exception should not interrupt other middleware
            return $this->executeMiddleware($message, $sequence);
        }
    }
}