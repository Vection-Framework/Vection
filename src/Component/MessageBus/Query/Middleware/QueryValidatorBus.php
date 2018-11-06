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

use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryBusSequenceInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Contracts\Validator\ValidationChainFailedExceptionInterface;
use Vection\Contracts\Validator\ValidationMediatorInterface;


/**
 * Class QueryValidatorBus
 *
 * @package Vection\Component\MessageBus\Query\Middleware
 */
class QueryValidatorBus implements QueryBusMiddlewareInterface
{
    /**
     * The validator that validates the payload of the query.
     *
     * @var ValidationMediatorInterface
     */
    protected $validator;

    /**
     * QueryValidatorBus constructor.
     *
     * @param ValidationMediatorInterface $validator
     */
    public function __construct(ValidationMediatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param QueryInterface            $message
     * @param QueryBusSequenceInterface $sequence
     *
     * @return ReadModelInterface|null
     *
     * @throws ValidationChainFailedExceptionInterface
     */
    public function __invoke(QueryInterface $message, QueryBusSequenceInterface $sequence)
    {
        # Create a new validation chain
        $validationChain = $this->validator->createValidationChain();

        # Let the query define its validation chain for its payload
        $message instanceof ValidatableInterface && $message->defineValidation($validationChain);

        # Validate the query payload data by the validation chain
        $this->validator->verify($validationChain, $message->payload()->toArray());

        # If data is valid then invoke next bus
        return $sequence->invokeNext($message);
    }
}