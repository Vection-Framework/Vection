<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 *  (c) Vection <project@vection.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Event\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Event\EventAnnotationMapper;
use Vection\Component\Event\EventManager;
use Vection\Component\Event\Tests\Fixtures\AnnotatedTestEvent;

/**
 * Class EventAnnotationMapperTest
 *
 * @package Vection\Component\Event\Tests
 */
class EventAnnotationMapperTest extends TestCase
{

    public function testAnnotatedEvent()
    {
        $eventManager = new EventManager();

        $mapper = new EventAnnotationMapper($eventManager);
        $mapper->addEventClassPath(__DIR__.'/Fixtures');
        $mapper->addHandlerClassPath(__DIR__.'/Fixtures');

        $event = new AnnotatedTestEvent('quark');
        $eventManager->dispatch($event);

        $this->assertTrue(\defined('TEST_ANNOTATED_EVENT_NAME'), 'Handler was not executed.');
        $this->assertEquals('quark', TEST_ANNOTATED_EVENT_NAME);
    }
}
