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
     * provided by the chosen provider.
     *
     * @param mixed[] $options
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
     * Rejects the message back to the MQ server in case it cannot be processes
     * by this consumer because of queue count limitation or other reasons.
     * The rejected message can be optionally requeue in case of e.g. temporary issues
     * or resource limitations.
     *
     * @param MessageInterface $message
     * @param bool             $requeue
     */
    public function reject(MessageInterface $message, bool $requeue = false): void;

    /**
     * Rejects all messages that already has been received and not fetched
     * for processing yet. By default, all these messages should be requeue.
     *
     * @param bool             $requeue
     */
    public function rejectAll(bool $requeue = true): void;
}
