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

use Vection\Contracts\Messenger\Service\Event\StateInterface;
use Vection\Contracts\Messenger\Service\Event\StateManagerInterface;

/**
 * Class Transition
 *
 * @package Vection\Component\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class StateManager implements StateManagerInterface
{
    /** @var string */
    protected $initialState;

    /** @var array */
    protected $transitions;

    /**
     * Transition constructor.
     *
     * @param string $initialState
     */
    public function __construct(string $initialState)
    {
        $this->initialState = $initialState;
        $this->transitions  = [];
    }

    /**
     * @param string $event
     * @param string $from
     * @param string $toActive
     * @param string $toPassive
     *
     * @return $this
     */
    public function addTransition(string $event, string $from, string $toActive, string $toPassive): StateManagerInterface
    {
        $this->transitions[$event] = [
            'from' => [$from],
            'toActive' => $toActive,
            'toPassive' => $toPassive
        ];

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function applyActive(StateInterface $state, object $eventObject): ?StateInterface
    {
        return $this->apply($state, $eventObject, 'toActive');
    }

    /**
     * @inheritDoc
     */
    public function applyPassive(StateInterface $state, object $eventObject): ?StateInterface
    {
        return $this->apply($state, $eventObject, 'toPassive');
    }

    /**
     * @param StateInterface $state
     * @param object         $eventObject
     * @param string         $stateType
     *
     * @return StateInterface|null
     */
    protected function apply(StateInterface $state, object $eventObject, string $stateType): ?StateInterface
    {
        $eventClassName = get_class($eventObject);

        if (! isset($this->transitions[$eventClassName])) {
            return null;
        }

        if (! in_array($state->getName(), $this->transitions[$eventClassName]['from'], true)) {
            return null;
        }

        return $state->withName($this->transitions[$eventClassName][$stateType]);
    }
}
