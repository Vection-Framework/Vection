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
use Vection\Component\Validator\Validator\Color;

/**
 * Class ColorTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class ColorTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(int $format, mixed ...$args): mixed
    {
        $rc = new Color($format);

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(int $format, mixed $color): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($format, $color));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(int $format, mixed $color): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($format, $color));
    }

    /**
     * @return mixed[]
     */
    public function provideValidValues(): array
    {
        $colors = [
            'HEX1' => '#abcdef',
            'HEX2' => '#123456',
            'HEX3' => '#98da1c',
            'RGB1' => 'rgb(123,255,0)',
            'RGB2' => 'rgb(99,25,100)',
            'RGB3' => 'rgb(3,5,0)',
        ];

        return [
            'HEX: #abcdef'            => [Color::HEX, $colors['HEX1']],
            'HEX: #123456'            => [Color::HEX, $colors['HEX2']],
            'HEX: #98da1c'            => [Color::HEX, $colors['HEX3']],
            'RGB: rgb(123,255,0)'     => [Color::RGB, $colors['RGB1']],
            'RGB: rgb(99,25,100)'     => [Color::RGB, $colors['RGB2']],
            'RGB: rgb(3,5,0)'         => [Color::RGB, $colors['RGB3']],
            'HEX+RGB: #abcdef'        => [Color::HEX | Color::RGB, $colors['HEX1']],
            'HEX+RGB: #123456'        => [Color::HEX | Color::RGB, $colors['HEX2']],
            'HEX+RGB: #98da1c'        => [Color::HEX | Color::RGB, $colors['HEX3']],
            'HEX+RGB: rgb(123,255,0)' => [Color::HEX | Color::RGB, $colors['RGB1']],
            'HEX+RGB: rgb(99,25,100)' => [Color::HEX | Color::RGB, $colors['RGB2']],
            'HEX+RGB: rgb(3,5,0)'     => [Color::HEX | Color::RGB, $colors['RGB3']],
        ];
    }

    /**
     * @return mixed[]
     */
    public function provideInvalidValues(): array
    {
        $colors = [
            'HEX1' => 'abc123',
            'HEX2' => '#defh87',
            'HEX3' => '#989xq7',
            'HEX4' => '#12d',
            'HEX5' => 'rgb(123,255,0)',
            'RGB1' => 'rgb(500,12,56)',
            'RGB2' => '99,25,100',
            'RGB3' => '#23655d',
            'RGB4' => 'rgba(23,65,5)',
        ];

        return [
            'HEX: abc123'             => [Color::HEX, $colors['HEX1']],
            'HEX: #defh87'            => [Color::HEX, $colors['HEX2']],
            'HEX: #989xq7'            => [Color::HEX, $colors['HEX3']],
            'HEX: #12d'               => [Color::HEX, $colors['HEX4']],
            'HEX: rgb(123,255,0)'     => [Color::HEX, $colors['HEX5']],
            'RGB: rgb(500,12,56)'     => [Color::RGB, $colors['RGB1']],
            'RGB: 99,25,100'          => [Color::RGB, $colors['RGB2']],
            'RGB: #23655d'            => [Color::RGB, $colors['RGB3']],
            'RGB: rgba(23,65,5)'      => [Color::RGB, $colors['RGB4']],
        ];
    }
}
