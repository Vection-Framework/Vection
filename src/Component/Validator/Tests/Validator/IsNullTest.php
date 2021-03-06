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
use stdClass;
use Vection\Component\Validator\Validator\IsNull;

/**
 * Class IsNullTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class IsNullTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new IsNull())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new IsNull())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            'NULL' => [null]
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            'string' => ['abc 123'],
            'bool'   => [false],
            'int'    => [123],
            'float'  => [987.654],
            'array'  => [[null]],
            'object' => [new stdClass()]
        ];
    }
}
