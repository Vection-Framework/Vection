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
use Vection\Component\Event\EventDispatcher;
use Vection\Component\Event\EventListenerProvider;
use Vection\Component\Event\Tests\Event\TestEvent;
use Vection\Component\Event\Tests\Listener\TestEventListener;

/**
 * Class EventDispatcherTest
 *
 * @package Vection\Component\Event\Tests
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventDispatcherTest extends TestCase
{

    /**
     *
     */
    public function testDispatch(): void
    {
        $provider = new EventListenerProvider();
        $provider->register(TestEventListener::class);

        $dispatcher = new EventDispatcher($provider);
        $dispatcher->dispatch(new TestEvent());

        $this->assertTrue(defined('TEST__EVENT_DISPATCHER_DISPATCH'));
    }

}
