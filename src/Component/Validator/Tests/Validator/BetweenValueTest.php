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
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\BetweenValue;

/**
 * Class BetweenValueTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class BetweenValueTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int|float $min, int|float $max, mixed ...$args): mixed
    {
        $rc = new BetweenValue($min, $max);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int|float $min, int|float $max, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($min, $max, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int|float $min, int|float $max, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($min, $max, $value));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            [1, 10, 5],
            [-10, 20, 7],
            [-10, 20, -4],
            [-10, -1, -2],
            [1.001, 8.5, 7.986],
            [-100.09, -59.2, -59.20001],
            [-1.78, 30.2, -0.005],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            [1, 10, 0],
            [-10, 20, -11],
            [-10, 20, 21],
            [-10, -1, 1],
            [1.98, 5.989, -3.12864],
            [-19.14, 87.036, -20],
            [-4, -2, 3],
        ];
    }
}
