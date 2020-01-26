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
 * Interface EventDispatcherInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EventDispatcherInterface
{
    /**
     * @param EventListenerFactoryInterface $eventListenerFactory
     */
    public function setListenerFactory(EventListenerFactoryInterface $eventListenerFactory): void;

    /**
     * @param StateProviderInterface $stateProvider
     */
    public function setStateProvider(StateProviderInterface $stateProvider): void;

    /**
     * @param string $className
     */
    public function registerListener(string $className): void;

    /**
     * @param MessageInterface $message
     */
    public function dispatch(MessageInterface $message): void;
}
