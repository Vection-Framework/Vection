<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\Iban;

/**
 * Class IbanTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class IbanTest extends TestCase
{
    /** @var Iban */
    protected $validator;

    public function setUp(): void
    {
        $this->validator = new Iban;
    }

    /**
     * @dataProvider provideToArrangedValues
     */
    public function testReArrange($actual, $expected)
    {
        $actual = $this->validator->normalize($actual);
        $rearranged = $this->validator->rearrange($actual);
        $this->assertEquals($rearranged,$expected, $expected);
    }

    /**
     * @dataProvider provideArrangedValues
     */
    public function testConvertingArrangementToInteger($actual, $expected)
    {
        $actual = $this->validator->convertToInteger($actual);
        $this->assertEquals($actual,$expected);
    }

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value)
    {
        $this->assertNull($this->validator->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value)
    {
        $this->assertNotNull($this->validator->validate($value));
    }

    /**
     * @return array
     */
    public function provideArrangedValues()
    {
        return [
            ['WEST12345698765432GB82',3214282912345698765432161182]
        ];
    }

    /**
     * @return array
     */
    public function provideToArrangedValues()
    {
        return [
            ['GB82 WEST 1234 5698 7654 32','WEST12345698765432GB82']
        ];
    }

    /**
     * @return array
     */
    public function provideValidValues()
    {
        return [
            ['GB82 WEST 1234 5698 7654 32']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues()
    {
        return [
            ['XC82 WEST 1234 5698 7654 32'],
            ['GB82 WEST 1234 5698 7654 32 889']
        ];
    }
}