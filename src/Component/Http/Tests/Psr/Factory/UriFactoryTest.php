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

namespace Vection\Component\Http\Tests\Psr\Factory;

use PHPUnit\Framework\TestCase;
use Vection\Component\Http\Psr\Message\Factory\UriFactory;

/**
 * Class UriFactoryTest
 *
 * @package Vection\Component\Http\Tests\Psr\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class UriFactoryTest extends TestCase
{

    public function testCreateUri()
    {
        $factory = new UriFactory();
        $uri     = $factory->createUri('https://www.test.com');

        $this->assertEquals('https', $uri->getScheme());
        $this->assertEquals('www.test.com', $uri->getHost());
        $this->assertEquals(443, $uri->getPort());
        $this->assertEquals('', $uri->getPath());
        $this->assertEquals('www.test.com:443', $uri->getAuthority());
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('', $uri->getFragment());
        $this->assertEquals('', $uri->getUserInfo());

        $uri = $factory->createUri('http://test.com:8080/context/path?unit=test#succes');

        $this->assertEquals('http', $uri->getScheme());
        $this->assertEquals('test.com', $uri->getHost());
        $this->assertEquals(8080, $uri->getPort());
        $this->assertEquals('/context/path', $uri->getPath());
        $this->assertEquals('test.com:8080', $uri->getAuthority());
        $this->assertEquals('unit=test', $uri->getQuery());
        $this->assertEquals('succes', $uri->getFragment());
        $this->assertEquals('', $uri->getUserInfo());

        $uri = $factory->createUri('git://foobar:secret@test.com');

        $this->assertEquals('git', $uri->getScheme());
        $this->assertEquals('test.com', $uri->getHost());
        $this->assertEquals(9418, $uri->getPort());
        $this->assertEquals('', $uri->getPath());
        $this->assertEquals('foobar:secret@test.com:9418', $uri->getAuthority());
        $this->assertEquals('', $uri->getQuery());
        $this->assertEquals('', $uri->getFragment());
        $this->assertEquals('foobar:secret', $uri->getUserInfo());
    }
}
