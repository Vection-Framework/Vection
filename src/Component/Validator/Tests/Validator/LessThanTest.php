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
use Vection\Component\Validator\Validator\LessThan;

/**
 * Class LessThanTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class LessThanTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($limit, $value): void
    {
        $this->assertNull((new LessThan($limit))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($limit, $value): void
    {
        $this->assertNotNull((new LessThan($limit))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '1 < 0'                 => [1, 0],
            '0 < -1'                => [0, -1],
            'true < false'          => [true, false],
            '0.0000000001 < 0.0'    => [1, 0.0000000001]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '0 < 1'                 => [0, 1],
            '-1 < 0'                => [-1, 0],
            'false < true'          => [false, true],
            '0.0 < 0.0000000001'    => [0.0, 0.0000000001]
        ];
    }
}
