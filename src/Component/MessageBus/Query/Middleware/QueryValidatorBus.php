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

namespace Vection\Component\MessageBus\Query\Middleware;

use Vection\Component\MessageBus\Exception\InvalidPayloadException;
use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryBusSequenceInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;
use Vection\Contracts\Validator\ValidatableInterface;

/**
 * Class QueryValidatorBus
 *
 * @package Vection\Component\MessageBus\Query\Middleware
 */
class QueryValidatorBus implements QueryBusMiddlewareInterface
{
    /**
     * @param QueryInterface            $message
     * @param QueryBusSequenceInterface $sequence
     *
     * @return ReadModelInterface|null
     *
     * @throws InvalidPayloadException
     */
    public function __invoke(QueryInterface $message, QueryBusSequenceInterface $sequence)
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