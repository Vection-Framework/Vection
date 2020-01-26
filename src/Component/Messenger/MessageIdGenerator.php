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
use Vection\Component\Messenger\Exception\RuntimeException;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;

/**
 * Class MessageIdGenerator
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageIdGenerator implements MessageIdGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(): string
    {
        try {
            $bytes = random_bytes(16);
        }
        catch (Exception $e) {
            throw new RuntimeException(
                'Insufficient OS ext tool requirements for generating unique IDs.', 0, $e
            );
        }

        # $byte will always have a valid value!
        $hex = bin2hex($bytes ?? '');

        $version = 4;

        $timeHi = substr($hex, 12, 4);
        $timeHi = hexdec($timeHi) & 0x0fff;
        $timeHi &= ~(0xf000);
        $timeHi |= $version << 12;

        $clockSeqHi = hexdec(substr($hex, 16, 2));
        $clockSeqHi &= 0x3f;
        $clockSeqHi &= ~(0xc0);
        $clockSeqHi |= 0x80;

        $fields = [
            'time_low' => substr($hex, 0, 8),
            'time_mid' => substr($hex, 8, 4),
            'time_hi_and_version' => str_pad(dechex($timeHi), 4, '0', STR_PAD_LEFT),
            'clock_seq_hi_and_reserved' => str_pad(dechex($clockSeqHi),2,'0',STR_PAD_LEFT),
            'clock_seq_low' => substr($hex, 18, 2),
            'node' => substr($hex, 20, 12),
        ];

        return vsprintf(
            '%08s-%04s-%04s-%02s%02s-%012s',
            $fields
        );
    }
}