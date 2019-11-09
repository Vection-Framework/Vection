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

/**
 * Interface MessageEncoderInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EncoderInterface
{
    /**
     * Encodes the serialized message and returns the result.
     *
     * @param string $serial
     *
     * @return string
     */
    public function encode(string $serial): string;

    /**
     * Decodes the encoded message serial and returns the serialized message.
     *
     * @param string $content
     *
     * @return string
     */
    public function decode(string $content): string;
}
