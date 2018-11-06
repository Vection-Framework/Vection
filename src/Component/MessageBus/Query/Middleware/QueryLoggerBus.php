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

namespace Vection\Component\MessageBus\Query\Middleware;

use Psr\Log\LoggerInterface;
use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryBusSequenceInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;

/**
 * Class QueryLoggerBus
 *
 * @package Vection\Component\MessageBus\Query\Middleware
 */
class QueryLoggerBus implements QueryBusMiddlewareInterface
{
    /**
     * This property contains a PSR supported logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LoggerQueryBus constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function __invoke(QueryInterface $query, QueryBusSequenceInterface $sequence)
    {
        try {
            if ( $sequence->hasNext() ) {
                $this->logBefore($query);
                $result = $sequence->invokeNext($query);
                $this->logAfter($query);

                return $result;
            }

            return null;
        } catch ( \Exception $ex ) {
            $this->logException($ex);
            throw $ex;
        }
    }

    /**
     * @param QueryInterface $query
     */
    public function logBefore(QueryInterface $query): void
    {
        $this->logger->info(sprintf('QueryBus: Send "%s".', ( new \ReflectionObject($query) )->getShortName()));
    }

    /**
     * @param QueryInterface $query
     */
    public function logAfter(QueryInterface $query): void
    {
        $this->logger->info(sprintf('QueryBus: Succeed handling "%s"', ( new \ReflectionObject($query) )->getShortName()));
    }

    /**
     * @param \Exception $ex
     */
    public function logException(\Exception $ex): void
    {
        $this->logger->critical(
            sprintf(_("Exception@QueryBus: %s \n%s in line %s\nTrace:\n %s"),
                $ex->getMessage(),
                $ex->getFile(),
                $ex->getLine(),
                $ex->getTraceAsString()
            )
        );
    }
}