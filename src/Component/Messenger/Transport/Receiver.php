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
use Vection\Contracts\Messenger\Transport\ReceiverInterface;
use Vection\Contracts\Messenger\Transport\SerializerInterface;

/**
 * Class Receiver
 *
 * @package Vection\Component\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Receiver implements ReceiverInterface
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
     * Sender constructor.
     */
    public function __construct()
    {
        $this->serializer = new DefaultSerializer();
    }

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
     * Unserializes and optionally decodes the content and returns an object of type MessageInterface.
     *
     * @param string $content
     *
     * @return MessageInterface
     */
    protected function transformDataToMessage(string $content): MessageInterface
    {
        if ($this->encoder !== null) {
            $content = $this->encoder->decode($content);
        }

        return $this->serializer->unserialize($content);
    }
}