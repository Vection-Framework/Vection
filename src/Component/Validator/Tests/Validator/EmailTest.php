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
use ReflectionException;
use ReflectionMethod;
use Vection\Component\Validator\Validator\Email;

/**
 * Class EmailTest
 *
 * @package Vection\Component\Validator\Tests\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class EmailTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getReflectionMethodOnValidate(mixed ...$args): mixed
    {
        $rc = new Email();

        $rm = new ReflectionMethod($rc, 'onValidate');
        $rm->setAccessible(true);

        return $rm->invokeArgs($rc, $args);
    }

    /**
     * @dataProvider provideValidValues
     *
     * @throws ReflectionException
     */
    public function testValidValues(mixed $value): void
    {
        self::assertTrue($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @dataProvider provideInvalidValues
     *
     * @throws ReflectionException
     */
    public function testInvalidValues(mixed $value): void
    {
        self::assertFalse($this->getReflectionMethodOnValidate($value));
    }

    /**
     * @return mixed[]
     *
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
     * @return mixed[]
     *
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
            ['John.Doe@' . $domain . $local],
            'Empty string'     => [''],
            'False'            => [false],
            'Null'             => [null],
            'Empty array'      => [[]],
        ];
    }
}
