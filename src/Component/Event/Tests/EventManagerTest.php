<?php declare(strict_types=1);

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
use Vection\Component\Event\Tests\Fixtures\TestEventListener;

/**
 * Class EventManagerTest
 *
 * @package Vection\Component\Event\Tests
 */
class EventManagerTest extends TestCase
{

    /**
     * Test the fire method by passing the event object as parameter.
     */
    public function testFireObjectEvent()
    {
        $eventManager = new EventManager();

        /** @var TestEventListener $handler */
        $handler = null;

        $eventManager->setEventListenerFactory(function($className) use (&$handler){
            # Only for testing the callback
            return $handler = new $className();
        });

        $eventManager->addEventListener(TestEvent::NAME, [TestEventListener::class, 'onSetString']);

        $event = new TestEvent('someEventData');
        $eventManager->fire($event);

        $this->assertEquals('someEventData', $handler->getString());
    }

    /**
     * Test the fire method by passing the event name as parameter.
     */
    public function testFireStringEvent()
    {
        $eventManager = new EventManager();

        $eventManager->addEventListener('vection.test', [TestDefaultEventHandler::class, 'onTestEvent']);
        $eventManager->fire('vection.test');

        //$this->assertEquals('vection.test', TEST_DEFAULT_EVENT_NAME);

        $this->assertTrue(true);
    }

    /**
     * Testing the notification of a listener which registers for wildcard event.
     */
    public function testFireEventWildcard()
    {
        $eventManager = new EventManager();
        $eventManager->setWildcardSeparator('.');

        /** @var TestEventListener $handler */
        $handler = null;
        $handlerInitCount = 0;

        $eventManager->setEventListenerFactory(function($className) use (&$handler, &$handlerInitCount){
            $handler = new $className();
            $handlerInitCount++;
            return $handler;
        });

        # This event listener register for wildcard event to be notified when fire 'vection.unit.test'
        $eventManager->addEventListener('vection.unit', [TestEventListener::class, 'onNotifyByWildcard']);

        # This two listeners should be ignored and the factory counter should stay at 1
        $eventManager->addEventListener('vection.php', [TestEventListener::class, 'onNotifyByWildcard']);
        $eventManager->addEventListener('vection.unit.unknown', [TestEventListener::class, 'onNotifyByWildcard']);

        $eventManager->fire(new TestEvent('works'));

        $this->assertNotNull($handler);
        $this->assertEquals('works', $handler->getString());
        $this->assertEquals(1, $handlerInitCount);
    }
}
