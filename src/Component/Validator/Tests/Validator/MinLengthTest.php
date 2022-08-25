<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * © Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use Exception;
use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\MinLength;

/**
 * Class MinLengthTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 *
 * @author BloodhunterD <vection@bloodhunterd.com>
 */
class MinLengthTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($length, $value): void
    {
        $this->assertNull((new MinLength($length))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($length, $value): void
    {
        $this->assertNotNull((new MinLength($length))->validate($value));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideValidValues(): array
    {
        return [
            [0, ' '],
            [-1, ''],
            [5, bin2hex(random_bytes(5))],
            [999999, bin2hex(random_bytes(500000))]
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        return [
            [1, '']
        ];
    }
}
