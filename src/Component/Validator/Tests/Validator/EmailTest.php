<?php
/**
 *  This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  © BloodhunterD <vection@bloodhunterd.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Vection\Component\Validator\Validator\Email;

/**
 * Class EmailTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class EmailTest extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new Email())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new Email())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            // Max length of the local part is 64 chars
            ['abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxyz@vection.de'],
            // Max length of a single domain label is 63 chars
            ['John.Doe@abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxy.vection.de'],
            // Max length of the whole domain, including dots, is 253 chars
            ['John.Doe@abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789_abc.vection.de'],
            // Printable ASCII chars
            ['!#$%&\'*+-/=?^_`{|}~@vection.de'],
            // Quoted dots as first, last or consecutively chars
            ['".John..Doe."@vection.de'],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            // Dot as first char
            ['.John.Doe@vection.de'],
            // Dot as last char
            ['John.Doe.@vection.de'],
            // Consecutively dots
            ['John..Doe@vection.de']
        ];
    }
}
