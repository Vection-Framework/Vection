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
    /**
     * @var MessageHeadersInterface
     */
    protected $headers;

    /**
     * @var null|object
     */
    protected $body;

    /**
     * Message constructor.
     *
     * @param object $body
     * @param array  $headers
     */
    public function __construct(object $body, array $headers = [])
    {
        $this->body    = $body;
        $this->headers = new MessageHeaders($headers);
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
    public function getBody()
    {
        return $this->body;
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
     * @param null|object $body
     *
     * @return static
     */
    public function withBody($body): MessageInterface
    {
        $message       = clone $this;
        $message->body = $body;

        return $message;
    }
}
