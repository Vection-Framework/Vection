<?php
/**
 *  This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  © BloodhunterD <vection@bloodhunterd.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use stdClass;
use Vection\Component\Validator\Validator\Boolean;

/**
 * Class BooleanTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class BooleanTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Boolean())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Boolean())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            'true'  => [true],
            'false' => [false]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            'NULL'      => [null],
            '0'         => [0],
            '1'         => [1],
            '-1'        => [-1],
            '0.123'     => [0.123],
            '-0.123'    => [-0.123],
            'abc'       => ['abc'],
            '"0"'       => ['0'],
            '"1"'       => ['1'],
            '"-1"'      => ['-1'],
            '[true]'    => [[true]],
            '[false]'   => [[false]],
            'stdClass'  => [new stdClass()]
        ];
    }
}
