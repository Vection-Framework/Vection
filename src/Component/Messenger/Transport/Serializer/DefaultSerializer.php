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

namespace Vection\Component\Messenger\Transport\Serializer;

use Vection\Component\Messenger\Message;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Transport\SerializerInterface;

/**
 * Class DefaultSerializer
 *
 * @package Vection\Component\Messenger\Transport\Serializer
 *
 * @author  David Lung <vection@davidlung.de>
 */
class DefaultSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     */
    public function serialize(MessageInterface $message): string
    {
        return serialize($message);
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $serial): MessageInterface
    {
        return unserialize($serial, [Message::class]);
    }
}