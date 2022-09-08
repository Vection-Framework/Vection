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
use Vection\Component\Validator\Validator\Ipv4;

/**
 * Class Ipv4Test
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Ipv4Test extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Ipv4();

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
            '127.0.0.1'       => ['127.0.0.1'],
            '0.0.0.0'         => ['0.0.0.0'],
            '255.255.255.255' => ['255.255.255.255'],
            '192.168.10.10'   => ['192.168.10.10'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '127.0.256.1'      => ['127.0.256.1'],
            '127.0.0.'         => ['127.0.0.'],
            '.1.1.1'           => ['.1.1.1'],
            '192.168.10.0/8'   => ['192.168.10.0/8'],
            'IP172.100.100.0'  => ['IP172.100.100.0'],
            '127:0:0:1'        => ['127:0:0:1'],
            '127-0-0-1'        => ['127-0-0-1'],
            '127000000001'     => [127000000001],
            '192 .168. 10.255' => ['192 .168. 10.255'],
            '127.000.000.001'  => ['127.000.000.001'],
            'Empty string'     => [''],
            'False'            => [false],
            'Null'             => [null],
            'Empty array'      => [[]],
        ];
    }
}
