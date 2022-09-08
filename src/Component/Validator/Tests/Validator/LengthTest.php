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
use Vection\Component\Validator\Validator\Length;

/**
 * Class LengthTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class LengthTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int $length, mixed ...$args): mixed
    {
        $rc = new Length($length);

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
            [1000000, bin2hex(random_bytes(500000))]
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
            [0, ' '],
            [1, ''],
            [5, bin2hex(random_bytes(5))],
            [999999, bin2hex(random_bytes(500000))]
        ];
    }
}
