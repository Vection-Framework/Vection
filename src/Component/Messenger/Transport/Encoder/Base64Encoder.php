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

namespace Vection\Component\Messenger\Transport\Encoder;

use RuntimeException;
use Vection\Contracts\Messenger\Transport\EncoderInterface;

/**
 * Class Base64Encoder
 *
 * @package Vection\Component\Messenger\Transport\Encoder
 *
 * @author  David Lung <vection@davidlung.de>
 */
class Base64Encoder implements EncoderInterface
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'base64';
    }

    /**
     * @inheritDoc
     */
    public function encode(string $serial): string
    {
        return base64_encode($serial);
    }

    /**
     * @inheritDoc
     */
    public function decode(string $content): string
    {
        $decodedContent = base64_decode($content);

        if (! is_string($decodedContent)) {
            throw new RuntimeException('Error while decoding content, please verify the content is valid.');
        }

        return $decodedContent;
    }
}
