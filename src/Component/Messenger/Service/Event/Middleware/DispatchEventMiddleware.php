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

namespace Vection\Component\Messenger\Service\Event\Middleware;

use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\Service\Event\EventDispatcherInterface;

/**
 * Class DispatchEventMiddleware
 *
 * @package Vection\Component\Messenger\Service\Event\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class DispatchEventMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * DispatchEventMiddleware constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $this->eventDispatcher->dispatch($message);

        return $sequence->next($message);
    }
}
