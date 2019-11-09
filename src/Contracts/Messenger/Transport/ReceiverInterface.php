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
     * Sets a serializer which is used to unserialize the received message.
     * If none serializer is set, this receiver uses the serializer which is defined
     * as default serializer from php configuration.
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void;

    /**
     * Sets an encoder which decodes a received serialized message.
     * If none encoder is set, this receiver uses the unserialized content.
     * The serializer and encoder of this receiver must match the setup
     * of the sender to create the message object successfully.
     *
     * @param EncoderInterface $encoder
     */
    public function setEncoder(EncoderInterface $encoder): void;

    /**
     * Starts the receiving and processing of messages until it
     * stops by an error or terminating in common way. This method
     * uses the given processor to pass the message for processing.
     *
     * @param ProcessorInterface $processor
     */
    public function receive(ProcessorInterface $processor): void;
}
