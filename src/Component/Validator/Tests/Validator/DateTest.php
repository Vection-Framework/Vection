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
use Vection\Component\Validator\Validator\Date;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class DateTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <vection@bloodhunterd.com>
 */
class DateTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(string $format, mixed ...$args): mixed
    {
        $rc = new Date($format);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(string $format, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($format, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(string $format, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($format, $value));
    }

    /**
     * @dataProvider provideInvalidTypes
     *
     * @throws ReflectionException
     */
    public function testInvalidTypes(string $format, mixed $value): void
    {
        $this->expectException(IllegalTypeException::class);
        $this->getReflectionMethodOnValidate($format, $value);
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        return [
            '2000-02-29 23:59:59' => ['Y-m-d H:i:s', '2000-02-29 23:59:59'],
            'Feb 29th 1960'       => ['M jS Y', 'Feb 29th 1960'],
            '5/31/2277'           => ['n/j/Y', '5/31/2277'],
            '1/2/20'              => ['j/n/y', '1/2/20'],
            '11am'                => ['ga', '11am'],
            '04:20'               => ['H:s', '04:20'],
            'December'            => ['F', 'December'],
            'Europe/Berlin'       => ['e', 'Europe/Berlin']
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '2000-02-30 00:00:00' => ['Y-m-d H:i:s', '2000-02-30 00:00:00'],
            '2000/02/30-00.00.00' => ['Y-m-d H:i:s', '2000/02/30-00.00.00'],
            'Feb 29th 1969'       => ['M jS Y', 'Feb 29th 1969'],
            'February 28th 2000'  => ['M jS Y', 'February 28th 2000'],
            '13/31/2277'          => ['n/j/Y', '13/31/2277'],
            '1/31/22'             => ['n/j/Y', '1/31/22'],
            '13pm'                => ['ga', '13pm'],
            '11 am'               => ['ga', '11 am'],
            '13:77'               => ['H:s', '13:77'],
            '12.12'               => ['H:s', '12.15'],
            '10-30'               => ['H:s', '10-30'],
            'Juli'                => ['F', 'Juli'],
            'Feb'                 => ['F', 'Feb'],
            'America/New York'    => ['e', 'America/New York'],
            'Europe-Berlin'       => ['e', 'Europe-Berlin'],
            'Australia.Sydney'    => ['e', 'Australia.Sydney'],
            'Africa'              => ['e', 'Africa'],
            'Empty string'        => ['Y-m-d', ''],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidTypes(): array
    {
        return [
            'False'       => ['Y-m-d', false],
            'True'        => ['Y-m-d', True],
            'Null'        => ['Y-m-d', null],
            'Empty array' => ['Y-m-d', []],
        ];
    }
}
