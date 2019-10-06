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
use stdClass;
use Vection\Component\Validator\Validator\Integer;

/**
 * Class IntegerTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class IntegerTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Integer())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Integer())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '0'             => [0],
            '-123'          => [-123],
            '999999999999'  => [999999999999],
            '1337'          => [1337],
            '0x539'         => [0x539],
            '02471'         => [02471],
            '0b10100111001' => [0b10100111001]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '1'      => ['1'],
            '1.0'    => [1.0],
            '0abc'   => ['0abc'],
            'abc123' => ['abc123'],
            'false'  => [false],
            '[123]'  => [[123]],
            'Object' => [new stdClass()]
        ];
    }
}
