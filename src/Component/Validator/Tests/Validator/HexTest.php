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
use Vection\Component\Validator\Validator\Hex;

/**
 * Class HexTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class HexTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Hex())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Hex())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '0123456789ABCDEF' => ['0123456789ABCDEF'],
            '0123456789abcdef' => ['0123456789abcdef']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '0123456789ABCDEFG' => ['0123456789ABCDEFG'],
            'abcdefg'           => ['abcdefg'],
            '012345 ABCDEF'     => ['012345 ABCDEF'],
        ];
    }
}
