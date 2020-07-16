<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Event;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Throwable;
use Vection\Contracts\Event\EventDispatcherInterface;
use Vection\Contracts\Event\EventInterface;
use Vection\Contracts\Event\EventListenerProviderInterface;

/**
 * Class EventDispatcher
 *
 * @package Vection\Component\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class EventDispatcher implements EventDispatcherInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var EventListenerProviderInterface
     */
    protected $listenerProvider;

    /**
     * @var bool
     */
    protected $catchExceptions;

    /**
     * EventDispatcher constructor.
     *
     * @param EventListenerProviderInterface $listenerProvider
     * @param bool                           $catchExceptions
     */
    public function __construct(EventListenerProviderInterface $listenerProvider, bool $catchExceptions = true)
    {
        $this->listenerProvider = $listenerProvider;
        $this->catchExceptions = $catchExceptions;
        $this->logger = new NullLogger();
    }

    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param object $event
     *   The object to process.
     *
     * @return object
     *   The Event that was passed, now modified by listeners.
     */
    public function dispatch(object $event): object
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);

        if ($c = count($listeners)) {
            $this->logger->debug(
                sprintf('Execute %d listeners for event %s', $c, str_replace('\\', '.', get_class($event)))
            );
        }else{
            $this->logger->debug(
                sprintf('No listeners for event %s', str_replace('\\', '.', get_class($event)))
            );
        }

        foreach ($listeners as $listener) {
            if ($event instanceof EventInterface && $event->isPropagationStopped()) {
                break;
            }

            if (!$this->catchExceptions) {
                $listener($event);
            }
            else{
                try{
                    $listener($event);
                }
                catch (Throwable $e) {
                    $this->logger->critical('Unexpected exception during execution of event listener.', [
                        'event' => get_class($event),
                        'listener' => is_object($listener) ? get_class($listener) : $listener,
                        'exception' => $e
                    ]);
                }
            }

        }

        return $event;
    }

}
