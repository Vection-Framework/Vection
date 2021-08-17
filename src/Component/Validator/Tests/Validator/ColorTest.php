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
use Vection\Component\Validator\Validator\Color;

/**
 * Class ColorTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class ColorTest extends TestCase
{

    /**
     * @dataProvider provideValidHexValues
     */
    public function testValidHexValues($color): void
    {
        self::assertNull((new Color(Color::HEX))->validate($color));
    }

    /**
     * @dataProvider provideInvalidHexValues
     */
    public function testInvalidHexValues($color): void
    {
        self::assertNotNull((new Color(Color::HEX))->validate($color));
    }

    /**
     * @dataProvider provideValidRgbValues
     */
    public function testValidRgbValues($color): void
    {
        self::assertNull((new Color(Color::RGB))->validate($color));
    }

    /**
     * @dataProvider provideInvalidRgbValues
     */
    public function testInvalidRgbValues($color): void
    {
        self::assertNotNull((new Color(Color::RGB))->validate($color));
    }

    /**
     * @dataProvider provideValidCombinedValues
     */
    public function testValidCombinedValues($color): void
    {
        self::assertNull((new Color(Color::HEX | Color::RGB))->validate($color));
    }

    /**
     * @dataProvider provideInvalidCombinedValues
     */
    public function testInvalidCombinedValues($color): void
    {
        self::assertNotNull((new Color(Color::HEX | Color::RGB))->validate($color));
    }

    /**
     * @return array
     */
    public function provideValidHexValues(): array
    {
        return [
            '#abcdef' => ['#abcdef'],
            '#123456' => ['#123456'],
            '#98da1c' => ['#98da1c'],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidHexValues(): array
    {
        return [
            'abc123' => ['abc123'],
            '#defh87' => ['#defh87'],
            '#989xq7' => ['#989xq7'],
            '#12d' => ['#12d'],
            'rgb(123,255,0)' => ['rgb(123,255,0)'],
        ];
    }

    /**
     * @return array
     */
    public function provideValidRgbValues(): array
    {
        return [
            'rgb(123,255,0)' => ['rgb(123,255,0)'],
            'rgb(99,25,100)' => ['rgb(99,25,100)'],
            'rgb(3,5,0)' => ['rgb(3,5,0)'],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidRgbValues(): array
    {
        return [
            'rgb(500,12,56)' => ['rgb(500,12,56)'],
            '99,25,100' => ['99,25,100'],
            'rgba(23,65,5)' => ['rgba(23,65,5)'],
            '#23655d' => ['#23655d'],
        ];
    }

    /**
     * @return array
     */
    public function provideValidCombinedValues(): array
    {
        return [
            '#abcdef' => ['#abcdef'],
            '#123456' => ['#123456'],
            '#98da1c' => ['#98da1c'],
            'rgb(123,255,0)' => ['rgb(123,255,0)'],
            'rgb(99,25,100)' => ['rgb(99,25,100)'],
            'rgb(3,5,0)' => ['rgb(3,5,0)'],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidCombinedValues(): array
    {
        return [
            'abc123' => ['abc123'],
            '#defh87' => ['#defh87'],
            '#989xq7' => ['#989xq7'],
            '#12d' => ['#12d'],
            'rgb(500,12,56)' => ['rgb(500,12,56)'],
            '99,25,100' => ['99,25,100'],
            'rgba(23,65,5)' => ['rgba(23,65,5)'],
        ];
    }
}
