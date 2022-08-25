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

namespace Vection\Component\Messenger\Transport;

use Vection\Component\Messenger\Exception\TransportException;
use Vection\Component\Messenger\Message;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\Transport\Encoder\Base64Encoder;
use Vection\Component\Messenger\Transport\Serializer\DefaultSerializer;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Transport\EncoderInterface;
use Vection\Contracts\Messenger\Transport\PackageInterface;
use Vection\Contracts\Messenger\Transport\PackerInterface;
use Vection\Contracts\Messenger\Transport\SerializerInterface;

/**
 * Class Packer
 *
 * @package Vection\Component\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
class Packer implements PackerInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var EncoderInterface
     */
    protected $encoder;

    /**
     * Packer constructor.
     *
     * @param SerializerInterface|null $serializer
     * @param EncoderInterface|null    $encoder
     */
    public function __construct(SerializerInterface $serializer = null, EncoderInterface $encoder = null)
    {
        $this->serializer = $serializer ?: new DefaultSerializer();
        $this->encoder    = $encoder ?: new Base64Encoder();
    }

    /**
     * @param MessageInterface $message
     *
     * @return PackageInterface
     */
    public function pack(MessageInterface $message): PackageInterface
    {
        if (! $message->getHeaders()->has(MessageHeaders::MESSAGE_TYPE)) {
            throw new TransportException(
                'Missing required message header "MESSAGE_TYPE" contains the body type for transport.'
            );
        }

        $encodedMessage = $this->encoder->encode(
            $this->serializer->serialize($message->getBody())
        );

        $meta = [
            'encoding' => $this->encoder->getName(),
            'fingerprint' => sha1(
                $message->getHeaders()->get(MessageHeaders::MESSAGE_TYPE).'|'.$encodedMessage
            )
        ];

        return new Package($meta, $message->getHeaders()->toArray(), $encodedMessage);
    }

    /**
     * @param PackageInterface $package
     *
     * @return MessageInterface
     */
    public function unpack(PackageInterface $package): MessageInterface
    {
        $headers = $package->getHeaders();

        if (! isset($headers[MessageHeaders::MESSAGE_TYPE])) {
            throw new TransportException(
                'Unable to unpack package because of missing header "MESSAGE_TYPE".'
            );
        }

        $body = $this->serializer->unserialize(
            $this->encoder->decode($package->getMessage()),
            $headers[MessageHeaders::MESSAGE_TYPE]
        );

        return new Message($body, $headers);
    }
}
