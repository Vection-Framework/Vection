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

namespace Vection\Component\Messenger;

use Exception;
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
     * @var MessageHeaders
     */
    protected $headers;

    /**
     * @var mixed|null
     */
    protected $payload;

    /**
     * Messenger constructor.
     *
     * @param mixed|null $payload
     * @param array      $headerUserData
     */
    public function __construct($payload, array $headerUserData = [])
    {
        try {
            $id = bin2hex(random_bytes(16));
        } catch (Exception $e) {
            $id = md5(uniqid((string) time(), true));
        }

        $this->payload = $payload;
        $this->headers = new MessageHeaders($id, time(), $headerUserData);
    }

    /**
     * @return MessageHeadersInterface
     */
    public function getHeaders(): MessageHeadersInterface
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
