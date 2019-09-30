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
use Vection\Component\Validator\Validator\Equals;

/**
 * Class EqualsTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class EqualsTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($valueA, $valueB): void
    {
        $this->assertNull((new Equals($valueA))->validate($valueB));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($valueA, $valueB): void
    {
        $this->assertNotNull((new Equals($valueA))->validate($valueB));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        $object = new stdClass();

        return [
            'Int'       => [0, 0],
            'Float'     => [123.456, 123.456],
            'String'    => ['Example', 'Example'],
            'Object'    => [$object, $object]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        $emptyObject = new stdClass();

        $filledObject = new stdClass();
        $filledObject->example = 'Example';

        return [
            'bool - int'            => [true, 1],
            'bool - empty string'   => [false, ''],
            'bool - string'         => [true, 'Example'],
            'int - string'          => [0, '0'],
            'int - empty string'    => [0, ''],
            'object - empty object' => [$filledObject, $emptyObject],
        ];
    }
}
