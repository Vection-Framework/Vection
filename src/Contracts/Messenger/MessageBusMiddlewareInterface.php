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
 * Interface MessageBusMiddlewareInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageBusMiddlewareInterface
{
    /**
     * Handles the message.
     * After this middleware is finished with handling, it must call
     * the next middleware by the sequence object and return its result.
     *
     * @param MessageInterface            $message
     * @param MiddlewareSequenceInterface $sequence
     *
     * @return MessageInterface
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface;
}
