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

namespace Vection\Component\Event\Tests\Fixtures;

use Vection\Component\Event\DefaultEvent;

/**
 * Class TestDefaultEventHandler
 *
 * @package Vection\Component\Event\Tests\Fixtures
 */
class TestDefaultEventHandler
{
    /**
     * @param DefaultEvent $event
     */
    public function onTestEvent(DefaultEvent $event): void
    {
        \define('TEST_DEFAULT_EVENT_NAME', $event->getName());
    }
}