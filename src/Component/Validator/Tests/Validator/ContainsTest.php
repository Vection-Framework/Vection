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
use Vection\Component\Validator\Validator\Contains;

/**
 * Class ContainsTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class ContainsTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($needle, $value): void
    {
        $this->assertNull((new Contains($needle))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($needle, $value): void
    {
        $this->assertNotNull((new Contains($needle))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            ['Needle', ['abc', 0, null, true, 'nope', new stdClass(), 'Needle']],
            ['Test', [1, false, null, 'string', -100, 'Test']],
            [
                'Goal', [
                    'key1' => 1,
                    null,
                    'Lorem ipsum',
                    'key3' => 1.337,
                    'key2' => true,
                    'Goal',
                    [],
                    new stdClass()
                ]
            ],
            [false, [null, 0, [], false]],
            [null, [0, false, '', 0.0, [], null]],
            [[], [0, false, '', 0.0, null, []]],
            [-10, [0, false, '', 0.0, null, [], -10]],
            [-0.5, [0, false, '', 0.0, null, [], -1, 20, -0.5]],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            ['Not found', ['lorem ipsum', 'Needl', 'eedle', 'needle']],
            [false, [null, 0, []]],
            [null, [0, false, '', 0.0, []]],
            [-10, [0, false, '', 0.0, null, [], -9]],
            [-0.5, [0, false, '', 0.0, null, [], -1, 20, -0.8]],
        ];
    }
}
