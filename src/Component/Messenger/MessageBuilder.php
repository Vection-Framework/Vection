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

/**
 * Class MessageBuilder
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageBuilder
{
    /**
     * @var array
     */
    protected $headerUserData = [];

    /**
     * @var object
     */
    protected $payload;

    /**
     * @param string $name
     * @param string $value
     *
     * @return MessageBuilder
     */
    public function withHeader(string $name, string $value): MessageBuilder
    {
        $this->headerUserData[$name] = $value;
        return $this;
    }

    /**
     * @param mixed $payload
     *
     * @return MessageBuilder
     */
    public function withPayload($payload): MessageBuilder
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * @return Message
     */
    public function build(): Message
    {
        return new Message($this->payload, $this->headerUserData);
    }
}