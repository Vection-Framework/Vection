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

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\Min;

/**
 * Class MinTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 *
 * @author BloodhunterD <vection@bloodhunterd.com>
 */
class MinTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($max, $value): void
    {
        $this->assertNull((new Min($max))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($max, $value): void
    {
        $this->assertNotNull((new Min($max))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '0 <= 1'        => [0, 1],
            '-1 <= 0'       => [-1, 0],
            'false <= true' => [false, true],
            '0.0 <= 1'      => [0.0, 1]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            [1, 0],
            [0, -1],
            [1, 0.001]
        ];
    }
}
