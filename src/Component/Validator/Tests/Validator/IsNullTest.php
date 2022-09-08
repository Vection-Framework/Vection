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
use stdClass;
use Vection\Component\Validator\Validator\IsNull;

/**
 * Class IsNullTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class IsNullTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new IsNull();

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            'Null' => [null]
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            'Empty string' => [''],
            'String'       => ['abc 123'],
            'False'        => [false],
            'True'         => [true],
            'Int'          => [123],
            'Float'        => [987.654],
            'Empty array'  => [[]],
            'Object'       => [new stdClass()],
        ];
    }
}
