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
        $tld = '.com';

        $domain = 'example';

        // Local mail part allows max 64 chars
        $local = bin2hex(random_bytes(32));

        $subDomain = implode('.', array_fill(0, 119, 'a')) . $tld;

        return [
            [$local . '@' . $domain . $tld],
            ['John.Doe@' . $subDomain . $tld],
            ['!#$%&\'*+-/=?^_`{|}~@' . $domain . $tld],
            ['".John..Doe."@' . $domain . $tld],
            ['"@"@' . $domain . $tld],
            ['"John"."Doe"@' . $domain . $tld],
            ['John.Doe@' . $domain . '.travelersinsurance'],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function provideInvalidValues(): array
    {
        $tld = '.com';

        $domain = 'example';

        // Local mail part allows max 64 chars
        $local = bin2hex(random_bytes(32));

        $subDomain = implode('.', array_fill(0, 126, 'a')) . $tld;

        return [
            ['John.Doe-' . $domain . $tld],
            ['.John.Doe.' . $domain . $tld],
            ['.John.Doe@' . $domain . $tld],
            ['John.Doe.@' . $domain . $tld],
            ['John..Doe@' . $domain . $tld],
            ['John@Doe@' . $domain . $tld],
            ['a"b(c)d,e:f;g<h>i[j\k]l@' . $domain . $tld],
            ['John"not"Doe@' . $domain . $tld],
            ['John"not\Doe@' . $domain . $tld],
            ['John\ still\"not\\Doe@' . $domain . $tld],
            [$local . 'a@' . $domain . $tld],
            ['John.Doe@' . $subDomain . $tld],
            ['John.Doe@' . $domain . $local]
        ];
    }
}
