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

namespace Vection\Component\Messenger\Transport\Serializer;

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
    public function serialize(object $message): string
    {
        return serialize($message);
    }

    /**
     * @inheritDoc
     */
    public function unserialize(string $serial, string $type): object
    {
        return unserialize($serial, ['allowed_classes' => $type]);
    }
}
