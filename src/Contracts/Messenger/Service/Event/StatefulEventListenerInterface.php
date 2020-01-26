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
 * Interface StatefulEventListenerInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface StatefulEventListenerInterface
{
    /**
     * @param StateManagerInterface $stateManager
     */
    public function defineTransitions(StateManagerInterface $stateManager): void;

    /**
     * @return StateInterface
     */
    public function getState(): StateInterface;

    /**
     * @param StateInterface $state
     */
    public function setState(StateInterface $state): void;
}