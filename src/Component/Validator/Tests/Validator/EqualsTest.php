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
use stdClass;
use Vection\Component\Validator\Validator\Equals;

/**
 * Class EqualsTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class EqualsTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed $value, mixed ...$args): mixed
    {
        $rc = new Equals($value);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(mixed $valueA, mixed $valueB): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($valueA, $valueB));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(mixed $valueA, mixed $valueB): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($valueA, $valueB));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        $object = new stdClass();

        return [
            'Int'       => [0, 0],
            'Float'     => [123.456, 123.456],
            'String'    => ['Example', 'Example'],
            'Object'    => [$object, $object]
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        $emptyObject = new stdClass();

        $filledObject          = new stdClass();
        $filledObject->example = 'Example';

        return [
            'bool - int'            => [true, 1],
            'bool - empty string'   => [false, ''],
            'bool - string'         => [true, 'Example'],
            'int - string'          => [0, '0'],
            'int - empty string'    => [0, ''],
            'object - empty object' => [$filledObject, $emptyObject],
        ];
    }
}
