<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Event\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Event\EventListenerProvider;
use Vection\Component\Event\Tests\Event\TestEvent;
use Vection\Component\Event\Tests\Listener\TestEventListener;
use Vection\Component\Event\Tests\Listener\TestEventListener2;

/**
 * Class EventListenerProviderTest
 *
 * @package Vection\Component\Event\Tests
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventListenerProviderTest extends TestCase
{

    public function testGetListenersForEvent(): void
    {
        $provider = new EventListenerProvider();
        $provider->register(TestEvent::class, TestEventListener::class);

        /** @var array $listeners */
        $listeners = $provider->getListenersForEvent(new TestEvent());

        $this->assertIsArray($listeners);
        $this->assertCount(1, $listeners);
        $this->assertCount(2, $listeners[0]);
        $this->assertInstanceOf(TestEventListener::class, $listeners[0][0]);
        $this->assertSame('__invoke', $listeners[0][1]);

        $provider->register(TestEvent::class, TestEventListener2::class);

        /** @var array $listeners */
        $listeners = $provider->getListenersForEvent(new TestEvent());

        $this->assertIsArray($listeners);
        $this->assertCount(2, $listeners);
        $this->assertCount(2, $listeners[1]);
        $this->assertInstanceOf(TestEventListener2::class, $listeners[1][0]);
        $this->assertSame('__invoke', $listeners[1][1]);
    }

}
