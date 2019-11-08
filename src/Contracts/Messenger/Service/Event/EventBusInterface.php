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

declare(strict_types = 1);

namespace Vection\Contracts\Messenger\Service\Event;

use Vection\Component\Message\Service\Event\Middleware\EventDispatcherMiddleware;

/**
 * Interface EventBusInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EventBusInterface
{
    /**
     * Pass the event to the message bus.
     * This method provides only a wrapped dispatching of the
     * message bus by creating a message with the given event as
     * payload and pass to the message bus instance. So make sure
     * the message bus has the correct setup and contains a related
     * event dispatcher middleware.
     *
     * @param mixed $event
     *
     * @see EventDispatcherMiddleware
     *
     */
    public function publish($event): void;
}