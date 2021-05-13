<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Messenger;

use Vection\Contracts\Messenger\MessageHeadersInterface;
use Vection\Contracts\Messenger\MessageInterface;

/**
 * Class Messenger
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class Message implements MessageInterface
{
    protected MessageHeadersInterface $headers;
    protected object $body;

    /**
     * Message constructor.
     *
     * @param object $body
     * @param array  $headers
     */
    public function __construct(object $body, array $headers = [])
    {
        $this->body    = $body;

        if (!isset($headers[MessageHeaders::MESSAGE_ID])) {
            $headers[MessageHeaders::MESSAGE_ID] = (new MessageIdGenerator())->generate();
        }

        $this->headers = new MessageHeaders($headers);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->headers->get(MessageHeaders::MESSAGE_ID);
    }

    /**
     * @return MessageHeadersInterface
     */
    public function getHeaders(): MessageHeadersInterface
    {
        return $this->headers;
    }

    /**
     * @return object
     */
    public function getBody(): object
    {
        return $this->body;
    }

    /**
     * @inheritDoc
     */
    public function getBodyType(): string
    {
        return is_object($this->body) ? str_replace('\\', '.', get_class($this->body)) : gettype($this->body);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function withHeader(string $name, string $value): MessageInterface
    {
        $message          = clone $this;
        $message->headers = new MessageHeaders(([$name => $value] + $this->headers->toArray()));

        return $message;
    }

    /**
     * @param object $body
     *
     * @return static
     */
    public function withBody(object $body): MessageInterface
    {
        $message       = clone $this;
        $message->body = $body;

        return $message;
    }
}
