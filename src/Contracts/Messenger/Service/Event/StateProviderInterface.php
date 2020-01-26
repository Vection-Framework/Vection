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

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface ProcessStateProviderInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface StateProviderInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return StateInterface|null
     */
    public function getState(MessageInterface $message): ?StateInterface;

    /**
     * @param StateInterface   $state
     * @param MessageInterface $message
     */
    public function addState(StateInterface $state, MessageInterface $message): void;

    /**
     * @param string $name
     *
     * @return StateInterface
     */
    public function createNewState(string $name): StateInterface;
}
