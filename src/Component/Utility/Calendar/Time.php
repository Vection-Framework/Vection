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

namespace Vection\Component\Utility\Calendar;

/**
 * Class Time
 *
 * @package Vection\Component\Utility\Calendar
 */
class Time
{
    public const SEC_PER_MIN  = 60;
    public const SEC_PER_HOUR = 3600;
    public const SEC_PER_DAY  = 86400;
    public const SEC_PER_WEEK = 604800;

    /**
     * @param int $time
     * @param string $format
     * @return string
     * @throws \Exception
     */
    public function relativize(int $time, string $format = 'Y-m-d'): string
    {
        $diff = (\time() - $time);

        if ( $diff >= 0 ) {
            # Calculate the past
            switch ( $diff ) {
                case $diff < 60:
                    return \_('just now');
                case $diff < 3600:
                    return \sprintf(\_('%s min ago'), \floor($diff / 60));
                case $diff < 86400:
                    return \sprintf(\_('%s hour ago'), \floor($diff / 3600));
                case $diff < (604800 * 2):
                    return \_('yesterday, ') . \date('h:i', $time);
            }
        }
        # Calculate the future

        return date($format, $time);
    }
}
