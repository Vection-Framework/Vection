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

use InvalidArgumentException;
use Vection\Component\Messenger\MessageBuilder;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\Service\Query\QueryDispatcherInterface;
use Vection\Contracts\Messenger\Service\Query\QueryInterface;

/**
 * Class QueryDispatcherMiddleware
 *
 * @package Vection\Component\Messenger\Service\Query\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class QueryDispatcherMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @var QueryDispatcherInterface
     */
    protected $queryDispatcher;

    /**
     * QueryDispatcherMiddleware constructor.
     *
     * @param QueryDispatcherInterface $queryDispatcher
     */
    public function __construct(QueryDispatcherInterface $queryDispatcher)
    {
        $this->queryDispatcher = $queryDispatcher;
    }

    /**
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $query = $message->getPayload();

        if (! $query instanceof QueryInterface) {
            throw new InvalidArgumentException('Expects a message payload of type QueryInterface');
        }

        $readModel = $this->queryDispatcher->dispatch($query);

        $message = (new MessageBuilder())->withPayload($readModel)->build();

        return $sequence->next($message);
    }
}
