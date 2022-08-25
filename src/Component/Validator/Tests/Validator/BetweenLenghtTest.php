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
use Vection\Component\Validator\Validator\BetweenLength;

/**
 * Class BetweenLenghtTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class BetweenLenghtTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($min, $max, $value): void
    {
        $this->assertNull((new BetweenLength($min, $max))->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($min, $max, $value): void
    {
        $this->assertNotNull((new BetweenLength($min, $max))->validate($value));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideValidValues(): array
    {
        $values = [
            [0, 64, 16],
            [10, 20, 10],
            [256, 512, 256]
        ];

        foreach ($values as $value) {
            $return[] = [$value[0], $value[1], bin2hex(random_bytes($value[2]))];
        }

        return ($return ?? []);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        $values = [
            [0, 64, 128],
            [10, 20, 2],
            [256, 512, 64]
        ];

        foreach ($values as $value) {
            $return[] = [$value[0], $value[1], bin2hex(random_bytes($value[2]))];
        }

        return ($return ?? []);
    }
}
