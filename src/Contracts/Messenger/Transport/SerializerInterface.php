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
 * Interface SerializerInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface SerializerInterface
{
    /**
     * Serializes the given data and return a string.
     * This will be used for serializing the message for transport.
     *
     * @param MessageInterface $message
     *
     * @return string
     */
    public function serialize(MessageInterface $message): string;

    /**
     * Unserialize the given serial and returns a message.
     * This will be used for unserialize the content from transport.
     *
     * @param string $serial
     *
     * @return MessageInterface
     */
    public function unserialize(string $serial): MessageInterface;
}
