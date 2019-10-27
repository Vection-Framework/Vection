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

namespace Vection\Component\MessageBus\Command\Middleware;

use Psr\Log\LoggerInterface;
use Vection\Contracts\MessageBus\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Command\CommandBusSequenceInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;

/**
 * Class CommandLoggerBus
 *
 * @package Vection\Component\MessageBus\Command\Middleware
 */
class CommandLoggerBus implements CommandBusMiddlewareInterface
{

    /**
     * This property contains a PSR supported logger.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * LoggerCommandBus constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param CommandInterface $command
     * @param CommandBusSequenceInterface $sequence
     *
     * @throws \Exception
     */
    public function __invoke(CommandInterface $command, CommandBusSequenceInterface $sequence)
    {
        try {
            if ( $sequence->hasNext() ) {
                $this->logBefore($command);
                $sequence->invokeNext($command);
                $this->logAfter($command);
            }
        } catch (\Exception $ex) {
            $this->logException($ex);
            throw $ex;
        }
    }

    /**
     * @param CommandInterface $command
     */
    public function logBefore(CommandInterface $command): void
    {
        $this->logger->info(sprintf('CommandBus: Send "%s".', (new \ReflectionObject($command))->getShortName()));
    }

    /**
     * @param CommandInterface $command
     */
    public function logAfter(CommandInterface $command): void
    {
        $this->logger->info(sprintf('CommandBus: Succeed handling "%s".', (new \ReflectionObject($command))->getShortName()));
    }

    /**
     * @param \Exception $ex
     */
    public function logException(\Exception $ex): void
    {
        $this->logger->critical(
            sprintf(
                "Exception@CommandBus: %s \n%s in line %s\nTrace:\n %s",
                $ex->getMessage(),
                $ex->getFile(),
                $ex->getLine(),
                $ex->getTraceAsString()
            )
        );
    }
}
