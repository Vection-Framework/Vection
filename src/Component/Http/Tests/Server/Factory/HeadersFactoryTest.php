<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Tests\Server\Factory;

use PHPUnit\Framework\TestCase;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Common\Factory\HeadersFactory;

/**
 * Class HeadersFactoryTest
 *
 * @package Vection\Component\Http\Tests\Server\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class HeadersFactoryTest extends TestCase
{

    public function testCreateHeaders(): void
    {
        $SERVER = [
            'CONTENT_TYPE' => 'application/json',
            'CONTENT_LENGTH' => 1024,
            'REDIRECT_HTTP_AUTHORIZATION' => 'XXXXX',
            'HTTP_HOST' => 'unit-test.com',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br'
        ];

        $factory = new HeadersFactory(new Environment($SERVER));
        $headers = $factory->createHeaders();

        $this->assertEquals('application/json', $headers->getLine('content-type'));
        $this->assertEquals(1024, $headers->getLine('content-length'));
        $this->assertEquals('XXXXX', $headers->getLine('Authorization'));
        $this->assertEquals('unit-test.com', $headers->getLine('Host'));
        $this->assertEquals('gzip, deflate, br', $headers->getLine('ACCEPT-ENCODING'));

        $SERVER['PHP_AUTH_USER'] = 'test-user';
        $SERVER['PHP_AUTH_PW']   = 'test-pw';

        $factory = new HeadersFactory(new Environment($SERVER));
        $headers = $factory->createHeaders();

        $this->assertEquals(
            'Basic '.base64_encode('test-user:test-pw'),
            $headers->getLine('Authorization')
        );

        $this->assertEmpty($headers->getLine('Accept-Language'));
    }
}
