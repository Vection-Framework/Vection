<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * © BloodhunterD <vection@bloodhunterd.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\Date;

/**
 * Class DateTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class DateTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($format, $value): void
    {
        $this->assertNull((new Date($format))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($format, $value): void
    {
        $this->assertNotNull((new Date($format))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '2000-02-29 23:59:59'   => ['Y-m-d H:i:s', '2000-02-29 23:59:59'],
            'Feb 29th 1960'         => ['M jS Y', 'Feb 29th 1960'],
            '5/31/2277'             => ['n/j/Y', '5/31/2277'],
            '1/2/20'                => ['j/n/y', '1/2/20'],
            '11am'                  => ['ga', '11am'],
            '04:20'                 => ['H:s', '04:20'],
            'December'              => ['F', 'December'],
            'Europe/Berlin'         => ['e', 'Europe/Berlin']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '2000-02-30 00:00:00'   => ['Y-m-d H:i:s', '2000-02-30 00:00:00'],
            '2000/02/30-00.00.00'   => ['Y-m-d H:i:s', '2000/02/30-00.00.00'],
            'Feb 29th 1969'         => ['M jS Y', 'Feb 29th 1969'],
            'February 28th 2000'    => ['M jS Y', 'February 28th 2000'],
            '13/31/2277'            => ['n/j/Y', '13/31/2277'],
            '1/31/22'               => ['n/j/Y', '1/31/22'],
            '13pm'                  => ['ga', '13pm'],
            '11 am'                 => ['ga', '11 am'],
            '13:77'                 => ['H:s', '13:77'],
            '12.12'                 => ['H:s', '12.15'],
            '10-30'                 => ['H:s', '10-30'],
            'Juli'                  => ['F', 'Juli'],
            'Feb'                   => ['F', 'Feb'],
            'America/New York'      => ['e', 'America/New York'],
            'Europe-Berlin'         => ['e', 'Europe-Berlin'],
            'Australia.Sydney'      => ['e', 'Australia.Sydney'],
            'Africa'                => ['e', 'Africa']
        ];
    }
}
