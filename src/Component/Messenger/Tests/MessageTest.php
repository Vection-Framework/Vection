<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Messenger\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Messenger\Message;

/**
 * Class MessageTest
 *
 * @package Vection\Component\Messenger\Tests
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageTest extends TestCase
{

    protected function getMessage(): Message
    {
        $body      = new \stdClass();
        $body->foo = 1;

        return new Message($body, ['TEST_HEADER_1' => 'foo', 'TEST_HEADER_2' => 'bar']);
    }

    public function testGetBody()
    {
        $body = $this->getMessage()->getBody();

        $this->assertIsObject($body);
        $this->assertSame(1, $body->foo);
    }

    public function testGetHeaders()
    {
        $headers = $this->getMessage()->getHeaders();

        $this->assertTrue($headers->has('TEST_HEADER_1'));
        $this->assertTrue($headers->has('TEST_HEADER_2'));
        $this->assertFalse($headers->has('TEST_HEADER_3'));

        $this->assertSame('foo', $headers->get('TEST_HEADER_1'));
        $this->assertSame('bar', $headers->get('TEST_HEADER_2'));
    }

    public function testWithBody()
    {
        $body      = new \stdClass();
        $body->bar = 42;

        $body = $this->getMessage()->withBody($body)->getBody();

        $this->assertIsObject($body);
        $this->assertSame(42, $body->bar);
    }

    public function testWithHeader()
    {
        $headers = $this->getMessage()->withHeader('TEST_HEADER_3', 'foobar')->getHeaders();

        $this->assertTrue($headers->has('TEST_HEADER_3'));
        $this->assertFalse($headers->has('TEST_HEADER_4'));

        $this->assertSame('foobar', $headers->get('TEST_HEADER_3'));
    }
}
