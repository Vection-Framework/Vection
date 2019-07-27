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

namespace Vection\Component\Event\Tests\Fixtures;

/**
 * Class TestEventHandler
 *
 * @package Vection\Component\Event\Tests\Fixtures
 */
class TestEventListener
{
    /** @var string */
    private $string = '';

    /**
     * @param TestEvent $event
     */
    public function onSetString(TestEvent $event): void
    {
        $this->string = $event->getString();
    }

    /**
     * @param TestEvent $event
     */
    public function onNotifyByWildcard(TestEvent $event): void
    {
        $this->string = $event->getString();
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }
}