<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Contracts\Event;

/**
 * Interface EventListenerInterface
 *
 * @package Vection\Contracts\Event
 */
interface EventListenerInterface
{
    /**
     * Returns the names of the subscribed events.
     *
     * @return string[]
     */
    public function getSubscribedEventNames(): array;

    /**
     * Returns the priority for the handler.
     * Priority: low [0 .. n] high.
     * @return int
     */
    public function getPriority(): int;

    /**
     * Returns the name of the method which handles the event.
     *
     * @return string
     */
    public function getHandlerMethodName(): string;
}
