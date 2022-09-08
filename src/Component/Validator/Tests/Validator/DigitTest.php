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
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Digit;

/**
 * Class DigitTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class DigitTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Digit();

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            '0'             => ['0'],
            '1337'          => ['1337'],
            '00101100'      => ['00101100'],
            '0x539'         => [0x539],
            '02471'         => [02471],
            '0b10100111001' => [0b10100111001]
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '100'                               => [100],
            '123.456'                           => [123.456],
            '1.337'                             => ['1.337'],
            '-1'                                => ['-1'],
            '1111æ1111'                         => ['1111æ1111'],
            '1337e0'                            => [1337e0],
            '9.1'                               => [9.1],
            '01010010010100100101001001010010 ' => ['01010010010100100101001001010010 '],
            'Empty string'                      => [''],
            'False'                             => [false],
            'Null'                              => [null],
            'Empty array'                       => [[]],
        ];
    }
}
