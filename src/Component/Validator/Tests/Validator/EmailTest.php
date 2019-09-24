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
            ['abcdefghijklmnopqrstuvwxyz-0123456789_abcdefghijklmnopqrstuvwxyz@vection.de'],
            ['John.Doe@abcdefghijklmnopqrstuvwxyz-0123456789-abcdefghijklmnopqrstuvwxy.vection.de'],
            ['John.Doe@abcdefghijklmnopqrstuvwxyz-0123456789-abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789-abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789-abcdefghijklmnopqrstuvwxy.abcdefghijklmnopqrstuvwxyz-0123456789-abc.vection.de'],
            ['!#$%&\'*+-/=?^_`{|}~@vection.de'],
            ['".John..Doe."@vection.de']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            ['.John.Doe@vection.de'],
            ['John.Doe.@vection.de'],
            ['John..Doe@vection.de']
        ];
    }
}
