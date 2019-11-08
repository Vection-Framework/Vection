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

declare(strict_types = 1);

namespace Vection\Component\Messenger\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Messenger\MessageBuilder;
use Vection\Contracts\Messenger\MessageInterface;

/**
 * Class MessageBuilderTest
 *
 * @package Vection\Component\Messenger\Tests
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageBuilderTest extends TestCase
{
    public function testBuild(): void
    {
        $message = (new MessageBuilder())->build();
        $this->assertInstanceOf(MessageInterface::class, $message);
    }

    public function testWithHeader(): void
    {
        $message = (new MessageBuilder())
            ->withHeader('foo', 'bar')
            ->build();

        $this->assertSame('bar', $message->getHeaders()->get('foo'));
    }

    public function testWithPayload(): void
    {
        $payload = ['test'];

        $message = (new MessageBuilder())
            ->withPayload($payload)
            ->build();

        $this->assertSame($payload, $message->getPayload());
    }
}
