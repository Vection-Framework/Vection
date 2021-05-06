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

namespace Vection\Component\Messenger;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Throwable;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MessageRelationInterface;
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
    protected array $middleware;
    protected array $defaultHeaders;
    protected MiddlewareSequenceFactoryInterface $middlewareSequenceFactory;
    protected MessageIdGeneratorInterface $idGenerator;

    /**
     * MessageBus constructor.
     *
     * @param array                                   $defaultHeaders
     * @param MiddlewareSequenceFactoryInterface|null $middlewareSequenceFactory
     * @param MessageIdGeneratorInterface|null        $idGenerator
     */
    public function __construct(
        array $defaultHeaders = [],
        MiddlewareSequenceFactoryInterface $middlewareSequenceFactory = null,
        MessageIdGeneratorInterface $idGenerator = null
    )
    {
        $this->logger = new NullLogger();
        $this->middlewareSequenceFactory = ($middlewareSequenceFactory ?: new MiddlewareSequenceFactory());
        $this->idGenerator = $idGenerator ?: new MessageIdGenerator();
        $this->defaultHeaders = $defaultHeaders;
    }

    /**
     * @inheritDoc
     */
    public function addDefaultHeader(string $name, string $value): void
    {
        $this->defaultHeaders[$name] = $value;
    }

    /**
     * @param MessageBusMiddlewareInterface $middleware
     */
    public function addMiddleware(MessageBusMiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $message, ?MessageRelationInterface $relation = null): MessageInterface
    {
        if (! $message instanceof MessageInterface) {
            $message = new Message($message);
        }

        $headers  = $message->getHeaders();
        $body     = $message->getBody();

        if (!$headers->has(MessageHeaders::MESSAGE_ID)) {
            $message = $message->withHeader(MessageHeaders::MESSAGE_ID, $this->idGenerator->generate());
        }

        if (!$headers->has(MessageHeaders::TIMESTAMP)) {
            $message = $message->withHeader(MessageHeaders::TIMESTAMP, (string) time());
        }

        if ( $relation ) {
            foreach ($relation->getHeaders()->toArray() as $name => $value) {
                $message = $message->withHeader($name, $value);
            }
        }

        foreach ($this->defaultHeaders as $headerName => $headerValue) {
            if (!$headers->has($headerName)) {
                $message = $message->withHeader($headerName, $headerValue);
            }
        }

        $bodyType = is_object($body) ? str_replace('\\', '.', get_class($body)) : gettype($body);

        $this->logger->debug(sprintf('DISPATCH %s (%s)', $headers->get(MessageHeaders::MESSAGE_ID), $bodyType));

        $sequence = $this->middlewareSequenceFactory->create($this->middleware);

        $result = $sequence->next($message);

        $this->logger->debug(sprintf('SUCCEEDED %s', $headers->get(MessageHeaders::MESSAGE_ID)));

        return $result;
    }
}
