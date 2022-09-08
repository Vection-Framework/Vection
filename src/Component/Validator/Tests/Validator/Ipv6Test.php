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
use Vection\Component\Validator\Validator\Ipv6;

/**
 * Class Ipv6Test
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Ipv6Test extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Ipv6();

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
            '2001:0db8:85a3:08d3:1319:8a2e:0370:7344' => ['2001:0db8:85a3:08d3:1319:8a2e:0370:7344'],
            '2001:db8:0:8d3:0:8a2e:70:7344'           => ['2001:db8:0:8d3:0:8a2e:70:7344'],
            '2001:0db8:0:0:0:0:1428:57ab'             => ['2001:0db8:0:0:0:0:1428:57ab'],
            '2001:db8::1428:57ab'                     => ['2001:db8::1428:57ab'],
            '2001:0db8:0:0:8d3:0:0:0'                 => ['2001:0db8:0:0:8d3:0:0:0'],
            '2001:db8:0:0:8d3::'                      => ['2001:db8:0:0:8d3::'],
            '2001:db8::8d3:0:0:0'                     => ['2001:db8::8d3:0:0:0'],
            '::ffff:7f00:1'                           => ['::ffff:7f00:1'],
            '::ffff:127.0.0.1'                        => ['::ffff:127.0.0.1'],
            '2001:0db8:1234:0000:0000:0000:0000:0000' => ['2001:0db8:1234:0000:0000:0000:0000:0000'],
            '2001:0db8:1234:ffff:ffff:ffff:ffff:ffff' => ['2001:0db8:1234:ffff:ffff:ffff:ffff:ffff'],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        return [
            '2001:db8::8d3::'                         => ['2001:db8::8d3::'],
            '1200:0000:AB00:1234:O000:2552:7777:1313' => ['1200:0000:AB00:1234:O000:2552:7777:1313'],
            'Empty string'                            => [''],
            'False'                                   => [false],
            'Null'                                    => [null],
            'Empty array'                             => [[]],
        ];
    }
}
