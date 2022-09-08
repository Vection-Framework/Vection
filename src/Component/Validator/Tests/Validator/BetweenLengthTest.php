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

use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\BetweenLength;

/**
 * Class BetweenLengthTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class BetweenLengthTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int $min, int $max, mixed ...$args): mixed
    {
        $rc = new BetweenLength($min, $max);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int $min, int $max, mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($min, $max, $value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int $min, int $max, mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($min, $max, $value));
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function provideValidValues(): array
    {
        $values = [
            [0, 64, 16],
            [10, 20, 10],
            [256, 512, 256]
        ];

        $return = [];
        foreach ($values as $value) {
            $return[] = [$value[0], $value[1], bin2hex(random_bytes($value[2]))];
        }

        return $return;
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        $values = [
            [0, 64, 128],
            [10, 20, 2],
            [256, 512, 64]
        ];

        $return = [];
        foreach ($values as $value) {
            $return[] = [$value[0], $value[1], bin2hex(random_bytes($value[2]))];
        }

        return $return;
    }
}
