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
use Vection\Component\Validator\Validator\Numeric;

/**
 * Class NumericTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class NumericTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Numeric())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Numeric())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '42'            => ['42'],
            '1337'          => ['1337'],
            '0x539'         => [0x539],
            '02471'         => [02471],
            '0b10100111001' => [0b10100111001],
            '1337e0'        => [1337e0],
            '9.1'           => [9.1]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            'not numeric' => ['not numeric'],
            '123 example' => ['123 example'],
            '[]'          => [[]],
            'NULL'        => [null],
            'false'       => [false],
            'true'        => [true]
        ];
    }
}
