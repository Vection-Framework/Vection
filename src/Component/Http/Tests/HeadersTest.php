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

namespace Vection\Component\Http\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Http\Headers;

/**
 * Class HeadersTest
 *
 * @package Vection\Component\Http\Tests
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class HeadersTest extends TestCase
{
    /**
     * @return Headers
     */
    private function getHeaders(): Headers
    {
        return new Headers(
            [
                'Content-Type' => ['text/html; charset=UTF-8'],
                'content-length' => ['3232'],
                'Date' => ['Wed, 04 Sep 2019 19:31:25 GMT'],
                'Accept-Language' => ['de-DE', 'de;q=0.9', 'en-US;q=0.8']
            ]
        );
    }

    public function testGet()
    {
        $this->assertEquals(
            ['de-DE', 'de;q=0.9', 'en-US;q=0.8'],
            $this->getHeaders()->get('Accept-Language')
        );

        $this->assertEquals(
            ['3232'],
            $this->getHeaders()->get('content-length')
        );

        $this->assertEquals(
            ['text/html; charset=UTF-8'],
            $this->getHeaders()->get('Content-Type')
        );
    }

    public function testGetLine()
    {
        $this->assertEquals(
            'de-DE, de;q=0.9, en-US;q=0.8',
            $this->getHeaders()->getLine('Accept-Language')
        );

        $this->assertEquals(
            'Wed, 04 Sep 2019 19:31:25 GMT',
            $this->getHeaders()->getLine('date')
        );
    }

    public function testAdd()
    {
        $headers = $this->getHeaders();

        $headers->add('Content-Type', 'application/json');
        $headers->add('Accept-Language', ['en;q=0.7', 'hu;q=0.6']);

        $this->assertEquals(
            ['text/html; charset=UTF-8', 'application/json'],
            $headers->get('Content-Type')
        );

        $this->assertEquals(
            ['de-DE', 'de;q=0.9', 'en-US;q=0.8', 'en;q=0.7', 'hu;q=0.6'],
            $headers->get('Accept-Language')
        );
    }

    public function testHasValue()
    {
        $this->assertTrue(
            $this->getHeaders()->hasValue('Accept-Language', 'de-DE')
        );

        $this->assertTrue(
            $this->getHeaders()->hasValue('Accept-Language', 'en-US;q=0.8')
        );

        $this->assertFalse(
            $this->getHeaders()->hasValue('Date', '19:31:25')
        );
    }

    public function testRemove()
    {
        $headers = $this->getHeaders();
        $headers->remove('Date');
        $this->assertFalse($headers->has('Date'));
    }

    public function testSet()
    {
        $headers = $this->getHeaders();

        $headers->set('Server', 'nginx');
        $headers->set('Content-Encoding', ['gzip']);
        $headers->set('Vary', ['Accept-Encoding', 'User-Agent']);
        $headers->set('Accept-Encoding', 'gzip, deflate,br');

        $this->assertEquals(['nginx'], $headers->get('Server'));
        $this->assertEquals(['gzip'], $headers->get('Content-Encoding'));
        $this->assertEquals(['Accept-Encoding', 'User-Agent'], $headers->get('Vary'));
        $this->assertEquals(['gzip', 'deflate', 'br'], $headers->get('Accept-Encoding'));
    }

    public function testHas()
    {
        $this->assertTrue(
            $this->getHeaders()->has('Accept-Language')
        );

        $this->assertTrue(
            $this->getHeaders()->has('accept-language')
        );
    }
}
