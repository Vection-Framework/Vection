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
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Component\Validator\Validator\Max;

/**
 * Class MaxTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class MaxTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int|float $max, mixed ...$args): mixed
    {
        $rc = new Max($max);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int|float $max, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($max, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int|float $max, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($max, $value));
    }

    /**
     * @dataProvider provideInvalidTypes
     *
     * @throws ReflectionException
     */
    public function testInvalidTypes(int|float $max, mixed $value): void
    {
        $this->expectException(IllegalTypeException::class);
        $this->getReflectionMethodOnValidate($max, $value);
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            [1, 0],
            [0, -1],
            [0.0000000001, 0.0],
            [0, 0],
            [1, 0.001],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            [0, 1],
            [-1, 0],
            [0.0, 1],
            [0.1, 0.1000000001],
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
