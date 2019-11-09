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

namespace Vection\Component\Messenger\Service\Event;

use Vection\Contracts\Event\EventManagerInterface;
use Vection\Contracts\Messenger\Service\Event\EventDispatcherInterface;

/**
 * Class EventDispatcher
 *
 * @package Vection\Component\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * EventDispatcher constructor.
     *
     * @param EventManagerInterface $eventManager
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @inheritDoc
     */
    public function dispatch($event): void
    {
        $this->eventManager->fire($event);
    }
}
