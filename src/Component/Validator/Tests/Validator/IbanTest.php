<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Component\Validator\Validator\Iban;

/**
 * Class IbanTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class IbanTest extends TestCase
{
    protected Iban $validator;

    public function setUp(): void
    {
        $this->validator = new Iban;
    }

    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Iban();

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
     * @dataProvider provideInvalidTypes
     *
     * @throws ReflectionException
     */
    public function testInvalidTypes(mixed $value): void
    {
        $this->expectException(IllegalTypeException::class);
        $this->getReflectionMethodOnValidate($value);
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            'GB82WEST12345698765432' => ['GB82 WEST 1234 5698 7654 32'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            'XC82WEST12345698765432'    => ['XC82 WEST 1234 5698 7654 32'],
            'GB82WEST12345698765432889' => ['GB82 WEST 1234 5698 7654 32 889'],
            'String'                    => ['123 example'],
            'Empty string'              => [''],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidTypes(): array
    {
        return [
            'Empty array' => [[]],
            'Null'        => [null],
            'False'       => [false],
            'True'        => [true],
        ];
    }
}
