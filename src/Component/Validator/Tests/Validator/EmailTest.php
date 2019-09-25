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

use Exception;
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
     * @throws Exception
     */
    public function provideValidValues(): array
    {
        $domain = 'vection.de';

        // Local mail part allows max 64 chars
        $local = bin2hex(random_bytes(32));

        $subDomain = implode('.', array_fill(0, 93, 'a')) . '.com';

        return [
            [$local . '@' . $domain],
            ['John.Doe@' . $subDomain],
            ['!#$%&\'*+-/=?^_`{|}~@' . $domain],
            ['".John..Doe."@' . $domain],
            ['"@"@' . $domain],
            ['"John"."Doe"@' . $domain]
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        $domain = 'vection.de';

        // Local mail part allows max 64 chars
        $local = bin2hex(random_bytes(32));

        return [
            ['John.Doe-' . $domain],
            ['.John.Doe.' . $domain],
            ['.John.Doe@' . $domain],
            ['John.Doe.@' . $domain],
            ['John..Doe@' . $domain],
            ['John@Doe@' . $domain],
            ['a"b(c)d,e:f;g<h>i[j\k]l@' . $domain],
            ['John"not"Doe@' . $domain],
            ['John"not\Doe@' . $domain],
            ['John\ still\"not\\Doe@' . $domain],
            [$local . 'a@' . $domain]
        ];
    }
}
