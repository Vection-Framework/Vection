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

namespace Vection\Component\Event\Annotation;

/**
 * Class Event
 *
 * @Annotation
 *
 * @package Vection\Component\Event\Annotation
 */
final class EventName
{
    /** @var string */
    private $event;

    /**
     * Event constructor.
     *
     * @param string $event
     */
    public function __construct(string $event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }
}