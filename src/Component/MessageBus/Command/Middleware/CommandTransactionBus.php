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

namespace Vection\Component\MessageBus\Command\Middleware;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Vection\Contracts\MessageBus\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Command\CommandBusSequenceInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\Command\CommandTransactionInterface;

/**
 * Class CommandTransactionBus
 *
 * @package Vection\Component\MessageBus\Command\Middleware
 */
class CommandTransactionBus implements CommandBusMiddlewareInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var CommandTransactionInterface */
    protected $transaction;

    /**
     * CommandTransactionBus constructor.
     *
     * @param CommandTransactionInterface $transaction
     */
    public function __construct(CommandTransactionInterface $transaction)
    {
        $this->transaction = $transaction;
        $this->logger = new NullLogger();
    }

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function __invoke(CommandInterface $command, CommandBusSequenceInterface $sequence)
    {
        if( $command->isTransactional() ){

            try{
                $this->logger->info('>> BEGIN transaction');
                $this->transaction->begin();
                $sequence->invokeNext($command);
                $this->transaction->commit();
                $this->logger->info('<< COMMIT transaction');
            }catch(\Exception $e){
                $this->logger->info('<< ROLLBACK transaction');
                $this->transaction->rollback();
                throw $e;
            }

        }else{
            $sequence->invokeNext($command);
        }
    }
}