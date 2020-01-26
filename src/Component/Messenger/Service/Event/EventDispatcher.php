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

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use Vection\Component\Messenger\Exception\RuntimeException;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Service\Event\EventDispatcherInterface;
use Vection\Contracts\Messenger\Service\Event\EventListenerFactoryInterface;
use Vection\Contracts\Messenger\Service\Event\StatefulEventListenerInterface;
use Vection\Contracts\Messenger\Service\Event\StateProviderInterface;

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
     * @var string[][][]
     */
    protected $listeners;

    /**
     * @var EventListenerFactoryInterface
     */
    protected $eventListenerFactory;

    /**
     * @var StateProviderInterface
     */
    protected $stateProvider;

    /**
     * @inheritDoc
     */
    public function setListenerFactory(EventListenerFactoryInterface $eventListenerFactory): void
    {
        $this->eventListenerFactory = $eventListenerFactory;
    }

    /**
     * @inheritDoc
     */
    public function setStateProvider(StateProviderInterface $stateProvider): void
    {
        $this->stateProvider = $stateProvider;
    }

    /**
     * @inheritDoc
     */
    public function registerListener(string $className): void
    {
        try {
            $reflection = new ReflectionClass($className);
            $methods    = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                if ( strpos($method->getName(), 'on') !== 0 ) {
                    continue;
                }

                $parameters = $method->getParameters();

                if ( count($parameters) === 0 ) {
                    continue;
                }

                $type = $parameters[0]->getType();

                if ( $type === null ) {
                    continue;
                }

                $eventClassName = $type->getName();

                $eventName = explode('\\', $eventClassName)[substr_count($eventClassName, '\\')];

                if ( $method->getName() !== 'on'.$eventName ) {
                    continue;
                }

                if (! isset($this->listeners[$eventClassName])) {
                    $this->listeners[$eventClassName] = [];
                }

                $this->listeners[$eventClassName][] = [
                    'class' => $className, 'method' => 'on'.$eventName
                ];
            }
        } catch (ReflectionException $e) {
            throw new RuntimeException('Unable to register process manager class.', 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function dispatch(MessageInterface $message): void
    {
        $eventObject    = $message->getBody();
        $eventClassName = get_class($eventObject);
        $stateDepleted  = false;

        if (isset($this->listeners[$eventClassName])) {
            $exceptions = [];
            $state      = null;

            foreach ( $this->listeners[$eventClassName] as $listenerInfo ) {

                $listenerClassName  = $listenerInfo['class'];
                $listenerMethodName = $listenerInfo['method'];

                if ($this->eventListenerFactory) {
                    $listener = $this->eventListenerFactory->createListener($listenerClassName, $message);
                } else {
                    $listener = new $listenerClassName($message);
                }

                if ($listener instanceof StatefulEventListenerInterface) {

                    if ($stateDepleted || ! $message->getHeaders()->has(MessageHeaders::CORRELATION_ID)) {
                        continue;
                    }

                    if ($this->stateProvider === null) {
                        throw new RuntimeException(
                            'The use of stateful event listeners requires a state provider.'
                        );
                    }

                    if ( $state === null ) {
                        $state = $this->stateProvider->getState($message);
                        if ( $state === null ) {
                            $state = $this->stateProvider->createNewState('START');
                        }
                    }

                    $stateManager = new StateManager('START');
                    $listener->defineTransitions($stateManager);

                    $newState = $stateManager->applyActive($state, $eventObject);

                    if ($newState === null) {
                        continue;
                    }

                    $this->stateProvider->addState($newState, $message);

                    $listener->setState($newState);

                    try {
                        $listener->{$listenerMethodName}($eventObject, $message);
                        $newState = $stateManager->applyPassive($state, $eventObject);
                        $this->stateProvider->addState($newState, $message);
                        $stateDepleted = true;
                    } catch (Throwable $e) {
                        $exceptions[] = $e;
                    }

                    continue;
                }

                try {
                    $listener->{$listenerMethodName}($eventObject, $message);
                } catch (Throwable $e) {
                    $exceptions[] = $e;
                }
            }

            if (count($exceptions) > 0) {
                # TODO!s
                throw new \RuntimeException('Exception during listener execution! '.$exceptions[0]->getMessage());
            }
        }
    }
}
