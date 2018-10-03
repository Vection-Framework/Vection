<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Event\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Event\EventManager;
use Vection\Component\Event\Tests\Fixtures\TestDefaultEventHandler;
use Vection\Component\Event\Tests\Fixtures\TestEvent;
use Vection\Component\Event\Tests\Fixtures\TestEventHandler;

/**
 * Class EventManagerTest
 *
 * @package Vection\Component\Event\Tests
 */
class EventManagerTest extends TestCase
{

    public function testDispatchEventObject()
    {
        $eventManager = new EventManager();

        /** @var TestEventHandler $handler */
        $handler = null;

        $eventManager->setHandlerFactoryCallback(function($className) use (&$handler){
            # Only for testing the callback
            return $handler = new $className();
        });

        $eventManager->addHandler(TestEvent::NAME, [TestEventHandler::class, 'onSetString']);

        $event = new TestEvent('someEventData');
        $eventManager->dispatch($event);

        $this->assertEquals('someEventData', $handler->getString());
    }

    public function testStringEvent()
    {
        $eventManager = new EventManager();

        $eventManager->addHandler('vection.test', [TestDefaultEventHandler::class, 'onTestEvent']);

        $eventManager->dispatch('vection.test');

        $this->assertEquals('vection.test', TEST_DEFAULT_EVENT_NAME);
    }
}
