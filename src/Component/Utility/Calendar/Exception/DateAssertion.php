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

namespace Vection\Component\Utility\Calendar\Exception;


/**
 * Class DateAssertion
 *
 * @package Vection\Component\Utility\Calendar\Exception
 */
class DateAssertion extends \Exception
{
    /**
     * @param string $start
     * @param string $end
     *
     * @throws DateAssertion
     */
    public static function invalidRange(string $start, string $end)
    {
        if ( strtotime($start) > strtotime($end) ) {
            throw new self('Date range is not valid, start is greater than end date');
        }
    }
}
