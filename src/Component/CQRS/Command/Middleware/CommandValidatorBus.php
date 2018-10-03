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

namespace Vection\Component\CQRS\Command\Middleware;

use Vection\Contracts\CQRS\Command\CommandBusSequenceInterface;
use Vection\Contracts\CQRS\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Contracts\Validator\ValidationChainFailedExceptionInterface;
use Vection\Contracts\Validator\ValidationMediatorInterface;

/**
 * Class CommandValidatorBus
 *
 * @package Vection\Component\CQRS\Command\Middleware
 */
class CommandValidatorBus implements CommandBusMiddlewareInterface
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
     * @param CommandInterface $message
     * @param CommandBusSequenceInterface $sequence
     *
     * @throws ValidationChainFailedExceptionInterface
     */
    public function __invoke(CommandInterface $message, CommandBusSequenceInterface $sequence)
    {
        # Create a new validation chain
        $validationChain = $this->validator->createValidationChain();

        # Let the command define its validation chain for its payload
        $message instanceof ValidatableInterface && $message->defineValidation($validationChain);

        # Validate the query payload data by the validation chain
        $this->validator->verify($validationChain, $message->payload()->toArray());

        $sequence->invokeNext($message);
    }
}