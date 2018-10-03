<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Event\Middleware;

use Psr\Log\LoggerInterface;
use Vection\Contracts\CQRS\Event\EventBusMiddlewareInterface;
use Vection\Contracts\CQRS\Event\EventBusSequenceInterface;
use Vection\Contracts\CQRS\Event\EventInterface;

/**
 * Class EventLoggerBus
 *
 * @package Vection\Component\CQRS\Event\Middleware
 */
class EventLoggerBus implements EventBusMiddlewareInterface
{
    /**
     * This property contains a PSR supported logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LoggerEventBus constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param EventInterface            $event
     * @param EventBusSequenceInterface $sequence
     *
     * @throws \Exception
     */
    public function __invoke(EventInterface $event, EventBusSequenceInterface $sequence)
    {
        try {
            if ( $sequence->hasNext() ) {
                $this->logBefore($event);
                $sequence->invokeNext($event);
                $this->logAfter($event);
            }
        } catch ( \Exception $ex ) {
            $this->logException($ex);
            throw $ex;
        }
    }

    /**
     * @param EventInterface $event
     */
    public function logBefore(EventInterface $event): void
    {
        $this->logger->info(sprintf('EventBus: Send "%s".', \get_class($event)));
    }

    /**
     * @param EventInterface $event
     */
    public function logAfter(EventInterface $event): void
    {
        $this->logger->info(sprintf('EventBus: Succeed handling "%s".', \get_class($event)));
    }

    /**
     * @param \Exception $ex
     */
    public function logException(\Exception $ex): void
    {
        $this->logger->critical(
            sprintf(_("Exception@EventBus: %s \n%s in line %s\nTrace:\n %s"),
                $ex->getMessage(),
                $ex->getFile(),
                $ex->getLine(),
                $ex->getTraceAsString()
            )
        );
    }
}