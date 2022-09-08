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

use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Component\Validator\Validator\MaxLength;

/**
 * Class MaxLengthTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class MaxLengthTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int $length, mixed ...$args): mixed
    {
        $rc = new MaxLength($length);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int $length, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($length, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int $length, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($length, $value));
    }

    /**
     * @dataProvider provideInvalidTypes
     *
     * @throws ReflectionException
     */
    public function testInvalidTypes(int $length, mixed $value): void
    {
        $this->expectException(IllegalTypeException::class);
        $this->getReflectionMethodOnValidate($length, $value);
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function provideValidValues(): array
    {
        return [
            [0, ''],
            [1, ' '],
            [10, bin2hex(random_bytes(5))],
            [1000000, bin2hex(random_bytes(500000))],
            'Array'              => [5, ['test', 0, true]],
            'Empty values array' => [9, ['', 0, false, null]],
        ];
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        return [
            [-1, ''],
            [5, bin2hex(random_bytes(5))],
            [999999, bin2hex(random_bytes(500000))],
            'Space'       => [0, ' '],
            'Empty array' => [-1, []],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidTypes(): array
    {
        return [
            'False'       => [0, false],
            'True'        => [1, True],
            'Null'        => [0, null],
        ];
    }
}
