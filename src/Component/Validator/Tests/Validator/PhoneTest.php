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
use Vection\Component\Validator\Validator\Phone;

/**
 * Class PhoneTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class PhoneTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Phone();

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
            '754-3010'           => ['754-3010'],
            '(541) 754-3010'     => ['(541) 754-3010'],
            '+1-541-754-3010'    => ['+1-541-754-3010'],
            '001-541-754-3010'   => ['001-541-754-3010'],
            '191 541 754 3010'   => ['191 541 754 3010'],
            '636-48018'          => ['636-48018'],
            '(089) / 636-48018'  => ['(089) / 636-48018'],
            '+49-89-636-48018'   => ['+49-89-636-48018'],
            '19-49-89-636-48018' => ['19-49-89-636-48018'],
            '020-30303030'       => ['020-30303030'],
            '07582-221434'       => ['07582-221434'],
            '+91-7582-221434'    => ['+91-7582-221434'],
            '011 61 2 9876 5432' => ['011 61 2 9876 5432'],
            '+7911 123456'       => ['+7911 123456'],
            '+447911123456'      => ['+447911123456'],
            '+49 611 1234'       => ['+49 611 1234'],
            '089 / 12345'        => ['089 / 12345'],
            '089/1234'           => ['089/1234'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '001-541-754-301O' => ['001-541-754-301O'],
            '001-JG1-PJG-D010' => ['001-JG1-PJG-D010'],
            '() 1234'          => ['() 1234'],
            'Empty string'     => [''],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidTypes(): array
    {
        return [
            'False'       => [false],
            'Null'        => [null],
            'Empty array' => [[]],
        ];
    }
}
