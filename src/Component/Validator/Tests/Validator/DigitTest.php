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
use Vection\Component\Validator\Validator\Digit;

/**
 * Class DigitTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class DigitTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Digit())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Digit())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '0'         => ['0'],
            '1337'      => ['1337'],
            '00101100'  => ['00101100']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '100'       => [100],
            '123.456'   => [123.456],
            '1.337'     => ['1.337'],
            '-1'        => ['-1'],
            '1111æ1111' => ['1111æ1111'],
            '01010010010100100101001001010010 ' => ['01010010010100100101001001010010 ']
        ];
    }
}
