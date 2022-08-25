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
 * Interface MiddlewareSequenceInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MiddlewareSequenceInterface
{
    /**
     * Returns the current middleware which handles the message.
     *
     * @return MessageBusMiddlewareInterface
     */
    public function getCurrent(): MessageBusMiddlewareInterface;

    /**
     * Executes the next middleware for message handling.
     *
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    public function next(MessageInterface $message): MessageInterface;
}
