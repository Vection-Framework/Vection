<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Component\Validator\Validator\Min;

/**
 * Class MinTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class MinTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int|float $min, mixed ...$args): mixed
    {
        $rc = new Min($min);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int|float $min, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($min, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int|float $min, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($min, $value));
    }

    /**
     * @dataProvider provideInvalidTypes
     *
     * @throws ReflectionException
     */
    public function testInvalidTypes(int|float $min, mixed $value): void
    {
        $this->expectException(IllegalTypeException::class);
        $this->getReflectionMethodOnValidate($min, $value);
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            [0, 1],
            [-1, 0],
            [0.0, 1],
            [1, 1.1],
            [-0.1, -0.05],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            [1, 0],
            [0, -1],
            [1, 0.001],
            [1.01, 1.0],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidTypes(): array
    {
        return [
            'Empty string' => [0, ''],
            'False'        => [0, false],
            'True'         => [1, True],
            'Null'         => [0, null],
            'Empty array'  => [0, []],
        ];
    }
}
