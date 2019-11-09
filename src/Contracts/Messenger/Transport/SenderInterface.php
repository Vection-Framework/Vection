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
 * Interface SenderInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface SenderInterface
{
    /**
     * Sets a serializer which is used to serialize the message before transport.
     * If none serializer is set, this sender uses the serializer which is defined
     * as default serializer from php configuration.
     *
     * @param SerializerInterface $serializer
     */
    public function setSerializer(SerializerInterface $serializer): void;

    /**
     * Sets an encoder which encode a serialized message before transport.
     * If none encoder is set, this sender sends only the serialization content.
     *
     * @param EncoderInterface $encoder
     */
    public function setEncoder(EncoderInterface $encoder): void;

    /**
     * Sends the serialized and optional encoded message via transport provider.
     * This methods takes optional a tag as second parameter, which can be optionally used
     * for common messaging purposes.
     *
     * @param MessageInterface $message
     * @param string           $tag
     */
    public function send(MessageInterface $message, string $tag = null): void;
}
