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

namespace Vection\Contracts\CQRS\Event;

/**
 * Interface EventDispatcherInterface
 *
 * @package Vection\Contracts\CQRS\Event
 */
interface EventDispatcherInterface
{
    /**
     * @param EventInterface $event
     */
    public function dispatch(EventInterface $event);
}