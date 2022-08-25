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
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\MessageRelation;

/**
 * Class MessageRelationTest
 *
 * @package Vection\Component\Messenger\Tests
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageRelationTest extends TestCase
{

    public function testCausedBy()
    {
        $mr = new MessageRelation();
        $mr->causedBy('abc-123-test');

        $headers = $mr->getHeaders();

        $this->assertTrue($headers->has(MessageHeaders::CAUSATION_ID));
        $this->assertSame('abc-123-test', $headers->get(MessageHeaders::CAUSATION_ID));
    }

    public function testWith()
    {
        $message = new Message(
            new \stdClass(),
            [
                MessageHeaders::CORRELATION_ID => 'correlation_test',
                MessageHeaders::MESSAGE_ID => '123456abcdef',
                'barFoo' => 'test'
            ]
        );

        $mr      = new MessageRelation();
        $headers = $mr->with($message)->getHeaders();

        $this->assertTrue($headers->has(MessageHeaders::CORRELATION_ID));
        $this->assertTrue($headers->has(MessageHeaders::CAUSATION_ID));
        $this->assertFalse($headers->has('barFoo'));

        $this->assertSame('correlation_test', $headers->get(MessageHeaders::CORRELATION_ID));
        $this->assertSame('123456abcdef', $headers->get(MessageHeaders::CAUSATION_ID));
    }

    public function testInCorrelation()
    {
        $mr = new MessageRelation();
        $mr->inCorrelation('xyz-789-foo');

        $headers = $mr->getHeaders();

        $this->assertTrue($headers->has(MessageHeaders::CORRELATION_ID));
        $this->assertSame('xyz-789-foo', $headers->get(MessageHeaders::CORRELATION_ID));
    }

    public function testGetHeaders()
    {
        # Returns always an instance of MessageHeadersInterface!
        $this->assertTrue(true);
    }
}
