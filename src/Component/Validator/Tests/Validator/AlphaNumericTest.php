<?php

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\AlphaNumeric;

/**
 * Class AlphaNumericTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class AlphaNumericTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value)
    {
        $this->assertNull((new AlphaNumeric())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value)
    {
        $this->assertNotNull((new AlphaNumeric())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues()
    {
        return [
            ['abc'],
            ['abc123'],
            ['ABC123'],
            ['AbC0DE'],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues()
    {
        return [
            ['123'],
            ['1abc'],
            ['%abcd123'],
            ['abc12!3'],
            ['abc12!3 '],
            ['abc12!"'],
        ];
    }
}
