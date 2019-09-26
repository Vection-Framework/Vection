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
    public function testValidValues($value): void
    {
        $this->assertNull((new Contains('Needle'))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Contains('Needle'))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            [
                ['abc', 0, null, true, 'nope', new stdClass(), 'Needle']
            ], [
                [1, false, null, 'string', -100, 'Needle']
            ], [
                [
                    'key1' => 1,
                    null,
                    'Lorem ipsum',
                    'key3' => 1.337,
                    'key2' => true,
                    'Needle'
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            [
                ['lorem ipsum', 'Needl', 'eedle', 'needle']
            ]
        ];
    }
}
