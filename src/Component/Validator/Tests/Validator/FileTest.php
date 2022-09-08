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
use Vection\Component\Validator\Validator\File;

/**
 * Class FileTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class FileTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new File();

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
            '__FILE__' => [__FILE__],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '__DIR__'      => [__DIR__],
            './'           => ['./'],
            '../'          => ['../'],
            'Empty string' => [''],
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
