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

namespace Vection\Contracts\Messenger\Transport;

use Throwable;
use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface ProcessorInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface ProcessorInterface
{
    /**
     * Returns the name of the channel which is used
     * to send and receive messages by message broker.
     * The name must be a unique simple name contains alpha characters.
     *
     * @return string
     */
    public function getTag(): string;

    /**
     * This method will be called by the message receiver instance
     * after a message is received from message broker server.
     *
     * @param MessageInterface $message
     */
    public function process(MessageInterface $message): void;

    /**
     * This method will be called by the message receiver if an error
     * occurs in the receiving process.
     *
     * @param Throwable $e The exception thrown by any step of message receiving.
     */
    public function onReceiverError(Throwable $e): void;

    /**
     * Returns true if this processor accepts the next message for processing
     * or false if this processor does not accept more messages. If no more
     * messages are accept, the receiver must stop receiving.
     *
     * @return bool True if active and waiting for message, otherwise false.
     */
    public function accept(): bool;
}
