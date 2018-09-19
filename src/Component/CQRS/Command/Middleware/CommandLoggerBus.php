<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Command\Middleware;

use Vection\Contracts\CQRS\Command\CommandBusSequenceInterface;
use Vection\Contracts\CQRS\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;
use Psr\Log\LoggerInterface;

/**
 * Class CommandLoggerBus
 *
 * @package Vection\Component\CQRS\Command\Middleware
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
        try{
            if( $sequence->hasNext() ){
                $this->logBefore($command);
                $sequence->invokeNext($command);
                $this->logAfter($command);
            }
        }catch(\Exception $ex){
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
            sprintf(_("Exception@CommandBus: %s \n%s in line %s\nTrace:\n %s"),
                $ex->getMessage(),
                $ex->getFile(),
                $ex->getLine(),
                $ex->getTraceAsString()
            )
        );
    }
}