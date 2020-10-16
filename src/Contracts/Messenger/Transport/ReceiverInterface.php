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

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface ReceiverInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface ReceiverInterface
{
    /**
     * Setup the receiver client with client specific options
     * provided by the choosed provider.
     *
     * @param array $options
     */
    public function setup(array $options = []): void;

    /**
     * Returns the next incoming message.
     *
     * If there is no message actually send from MQ server,
     * this method returns null.
     *
     * @return MessageInterface|null
     */
    public function next(): ?MessageInterface;

    /**
     * Sends an acknowledgement flag to the MQ server.
     *
     * @param MessageInterface $message
     */
    public function ack(MessageInterface $message): void;

    /**
     * Sends an negative acknowledgement flag to the MQ server.
     * NACKed messages will be not requeued by default, use the second
     * parameter to change this default behavior.
     *
     * @param MessageInterface $message
     * @param bool             $requeue
     */
    public function nack(MessageInterface $message, bool $requeue = false): void;

    /**
     * Rejects the message back to the MQ server in case it cannot be processes
     * by this consumer because of queue count limitation or other reasons.
     * A rejection of a message is usually used to requeue the message immediately
     * in order that the MQ server can e.g. send it to an other consumer which
     * is able to process the message.
     *
     * @param MessageInterface $message
     */
    public function reject(MessageInterface $message): void;
}
