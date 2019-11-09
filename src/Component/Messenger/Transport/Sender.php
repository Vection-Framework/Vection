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

namespace Vection\Component\Messenger\Transport;

use Vection\Component\Messenger\Transport\Serializer\DefaultSerializer;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Transport\EncoderInterface;
use Vection\Contracts\Messenger\Transport\SenderInterface;
use Vection\Contracts\Messenger\Transport\SerializerInterface;

/**
 * Class Sender
 *
 * @package Vection\Component\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Sender implements SenderInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EncoderInterface|null
     */
    protected $encoder;

    /**
     * @inheritDoc
     */
    public function setSerializer(SerializerInterface $serializer): void
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function setEncoder(EncoderInterface $encoder): void
    {
        $this->encoder = $encoder;
    }

    /**
     * Serializes and optionally encodes the message and returns a transportable content.
     *
     * @param MessageInterface $message
     *
     * @return string
     */
    protected function transformMessage(MessageInterface $message): string
    {
        if ($this->serializer === null) {
            $this->serializer = new DefaultSerializer();
        }

        $transportData = $this->serializer->serialize($message);

        if ($this->encoder !== null) {
            $transportData = $this->encoder->encode($transportData);
        }

        return $transportData;
    }
}