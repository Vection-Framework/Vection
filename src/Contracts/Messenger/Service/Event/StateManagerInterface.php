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

/**
 * Interface TransitionInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface StateManagerInterface
{
    /**
     * @param string $event
     * @param string $from
     * @param string $toActive
     * @param string $toPassive
     *
     * @return $this
     */
    public function addTransition(string $event, string $from, string $toActive, string $toPassive): self;

    /**
     * @param StateInterface $state
     * @param object         $eventObject
     *
     * @return StateInterface|null
     */
    public function applyActive(StateInterface $state, object $eventObject): ?StateInterface;

    /**
     * @param StateInterface $state
     * @param object         $eventObject
     *
     * @return StateInterface|null
     */
    public function applyPassive(StateInterface $state, object $eventObject): ?StateInterface;
}
