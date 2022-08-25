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

namespace Vection\Contracts\Messenger;

/**
 * Interface MessageBusInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageBusInterface
{
    /**
     * Adds a new middleware to the message bus.
     * The order of adding the middleware is the order they will
     * be executed by the middleware sequence object.
     *
     * @param MessageBusMiddlewareInterface $middleware
     */
    public function addMiddleware(MessageBusMiddlewareInterface $middleware): void;

    /**
     * Add a default header which is passed to every message before dispatching.
     * The header does not overwrite existing headers of the message.
     *
     * @param string $name
     * @param string $value
     */
    public function addDefaultHeader(string $name, string $value): void;

    /**
     * Starts dispatching the message and executes the middleware by using
     * the sequence object. At the end of the dispatching this method returns
     * the result of the handling by each middleware. The returned message is
     * the message which is passed for dispatching or a new message created by
     * one of the middleware instances.
     *
     * @param object $message An object of type MessageInterface or an any object.
     *                                               If any object is given which is not from type MessageInterface then
     *                                               this object will be wrapped by the default Message implementation whereas
     *                                               the object is treated as the message body.
     * @param MessageRelationInterface|null $relation A message relation which adds the additionally message headers
     *                                                correlation and causation to the message.
     *
     * @return MessageInterface
     */
    public function dispatch(object $message, ?MessageRelationInterface $relation = null): MessageInterface;
}
