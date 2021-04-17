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
use Vection\Component\Validator\Validator\Timezone;

/**
 * Class TimezoneTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class TimezoneTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($locale): void
    {
        self::assertNull((new Timezone())->validate($locale));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($locale): void
    {
        self::assertNotNull((new Timezone())->validate($locale));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            'Europe/Berlin' => ['Europe/Berlin'],
            'America/New_York' => ['America/New_York']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            'Europe' => ['Europe'],
            'Europe\\Berlin' => ['Europe\\Berlin'],
            'Europa/Berlin' => ['Europa/Berlin'],
            'America/NewYork' => ['America/NewYork'],
            'NewYork' => ['NewYork']
        ];
    }
}
