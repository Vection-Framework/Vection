<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Command\Middleware;

use Vection\Component\MessageBus\Exception\InvalidPayloadException;
use Vection\Contracts\MessageBus\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Command\CommandBusSequenceInterface;
use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\Validator\ValidatableInterface;

/**
 * Class CommandValidatorBus
 *
 * @package Vection\Component\MessageBus\Command\Middleware
 */
class CommandValidatorBus implements CommandBusMiddlewareInterface
{
    /**
     * @param CommandInterface            $message
     * @param CommandBusSequenceInterface $sequence
     *
     * @throws InvalidPayloadException
     */
    public function __invoke(CommandInterface $message, CommandBusSequenceInterface $sequence)
    {
        if( $message instanceof ValidatableInterface ){
            $chain = $message->getValidationChain();
            $chain->verify($message->payload()->toArray());

            if( $violations = $chain->getViolations() ){
                throw new InvalidPayloadException($violations);
            }
        }

        # If data is valid then invoke next bus
        return $sequence->invokeNext($message);
    }
}