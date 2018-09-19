<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Event\Tests;

use Vection\Component\Event\EventManager;
use Vection\Component\Event\Tests\Fixtures\TestEvent;
use Vection\Component\Event\Tests\Fixtures\TestEventHandler;

class EventManagerTest extends \PHPUnit_Framework_TestCase
{

    public function testDispatch()
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
}
