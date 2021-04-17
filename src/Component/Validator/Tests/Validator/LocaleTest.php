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
use Vection\Component\Validator\Validator\Locale;

/**
 * Class LocaleTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class LocaleTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($locale): void
    {
        self::assertNull((new Locale())->validate($locale));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($locale): void
    {
        self::assertNotNull((new Locale())->validate($locale));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            'de-DE' => ['de-DE'],
            'zh-Hans-CN' => ['zh-Hans-CN']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            'de' => ['de'],
            'DE' => ['DE'],
            'de_DE' => ['de_DE'],
            'zh-Hans' => ['zh-Hans'],
            'Hans-CN' => ['Hans-CN'],
            'zh_Hans_CN' => ['zh_Hans_CN']
        ];
    }
}
