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
use Vection\Component\Validator\Validator\PhoneE164;

/**
 * Class PhoneE164Test
 *
 * @package Vection\Component\Validator\Tests\Validator
 */
class PhoneE164Test extends TestCase
{

    /**
     * @dataProvider provideValidValues
     */
    public function testValidValues($value): void
    {
        $this->assertNull((new PhoneE164())->validate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     */
    public function testInvalidValues($value): void
    {
        $this->assertNotNull((new PhoneE164())->validate($value));
    }

    /**
     * @return array
     */
    public function provideValidValues(): array
    {
        return [
            '+1 541 7543010'  => ['+1 541 7543010'],
            '+917582 221434'  => ['+917582 221434'],
            '+7911 123456'    => ['+7911 123456'],
            '+447911123456'   => ['+447911123456'],
            '+49 6111234'     => ['+49 6111234'],
            '+1 999 555 0123' => ['+1 999 555 0123']
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidValues(): array
    {
        return [
            '+1 541 754301O'     => ['+1 541 754301O'],
            '001-JG1-PJG-D010'   => ['001-JG1-PJG-D010'],
            '191 541 754 3010'   => ['191 541 754 3010'],
            '636-48018'          => ['636-48018'],
            '(089) / 636-48018'  => ['(089) / 636-48018'],
            '19-49-89-636-48018' => ['19-49-89-636-48018'],
            '020-30303030'       => ['020-30303030'],
            '07582-221434'       => ['07582-221434'],
            '011 61 2 9876 5432' => ['011 61 2 9876 5432'],
            '089 / 12345'        => ['089 / 12345'],
            '089/1234'           => ['089/1234'],
            ''                   => [''],
            ' '                  => [' '],
        ];
    }
}
