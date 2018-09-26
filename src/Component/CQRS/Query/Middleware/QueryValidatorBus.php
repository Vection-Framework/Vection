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

namespace Vection\Component\CQRS\Query\Middleware;

use Vection\Contracts\CQRS\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\CQRS\Query\QueryBusSequenceInterface;
use Vection\Contracts\CQRS\Query\QueryInterface;
use Vection\Contracts\CQRS\Query\ReadModelInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Contracts\Validator\ValidationChainFailedExceptionInterface;
use Vection\Contracts\Validator\ValidatorInterface;


/**
 * Class QueryValidatorBus
 *
 * @package Vection\Component\CQRS\Query\Middleware
 */
class QueryValidatorBus implements QueryBusMiddlewareInterface
{
    /**
     * The validator that validates the payload of the query.
     *
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * QueryValidatorBus constructor.
     *
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
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