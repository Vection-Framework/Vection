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
            ['Bool true' => true],
            ['Bool false' => false]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            ['NULL' => null],
            ['Int 0' => 0],
            ['int 1' => 1],
            ['Int -1' => -1],
            ['Float 0.123' => 0.123],
            ['Float -0.123' => -0.123],
            ['String abc' => 'abc'],
            ['String 0' => '0'],
            ['String 1' => '1'],
            ['String -1' => '-1'],
            ['Array [true]' => [true]],
            ['Array [false]' => [false]],
            ['Object stdClass' => new stdClass()]
        ];
    }
}
