<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Timezone;

/**
 * Class TimezoneTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class TimezoneTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Timezone();

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
            'Europe/Berlin'    => ['Europe/Berlin'],
            'America/New_York' => ['America/New_York']
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            'Europe'           => ['Europe'],
            'Europe\\Berlin'   => ['Europe\\Berlin'],
            'Europa/Berlin'    => ['Europa/Berlin'],
            'America/NewYork'  => ['America/NewYork'],
            'America/New York' => ['America/New York'],
            'NewYork'          => ['NewYork'],
            'Empty string'     => [''],
            'False'            => [false],
            'Null'             => [null],
            'Empty array'      => [[]],
        ];
    }
}
