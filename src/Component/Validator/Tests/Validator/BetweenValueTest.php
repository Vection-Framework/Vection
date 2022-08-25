<?php

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\BetweenValue;

/**
 * Class BetweenValueTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class BetweenValueTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value, $min, $max): void
    {
        $this->assertNull((new BetweenValue($min, $max))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value, $min, $max): void
    {
        $this->assertNotNull((new BetweenValue($min, $max))->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            [5, 1, 10],
            [7, -10, 20],
            [-4, -10, 20],
            [-2, -10, -1]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            [0, 1, 10],
            [-11, -10, 20],
            [21, -10, 20],
            [1, -10, -1]
        ];
    }
}
