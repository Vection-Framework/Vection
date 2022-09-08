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
use Vection\Component\Validator\Validator\GreaterThan;

/**
 * Class GreaterThanTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class GreaterThanTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int|float $limit, mixed ...$args): mixed
    {
        $rc = new GreaterThan($limit);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int|float $limit, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($limit, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int|float $limit, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($limit, $value));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            '1 > 0'              => [0, 1],
            '0 > -1'             => [-1, 0],
            '0.0000000001 > 0.0' => [0.0, 0.0000000001]
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '0 > 1'              => [1, 0],
            '-1 > 0'             => [0, -1],
            '-0.01 > true'       => [-0.01, false],
            '0.0 > 0.0000000001' => [0.0000000001, 0.0]
        ];
    }
}
