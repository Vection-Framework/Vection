<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 *  (c) Vection <project@vection.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Event;

/**
 * Class DefaultEvent
 *
 * @package Vection\Component\Event
 */
class DefaultEvent extends Event
{
    /**
     * The name of the event.
     * This name is used to identify a specific registered event.
     *
     * @var string
     */
    protected $eventName;

    /**
     * DefaultEvent constructor.
     *
     * @param string $eventName
     */
    public function __construct(string $eventName)
    {
        $this->eventName = $eventName;
    }

    /**
     * Returns the name of this event.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->eventName;
    }
}