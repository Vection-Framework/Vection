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

namespace Vection\Component\Validator;

use Vection\Component\Validator\Exception\ValidationChainFailedException;
use Vection\Component\Validator\Exception\ValidationFailedException;
use Vection\Component\Validator\Validator\GeneralValidator;
use Vection\Contracts\Validator\ValidationChainInterface;
use Vection\Contracts\Validator\ValidationFailedExceptionInterface;
use Vection\Contracts\Validator\ValidationMediatorInterface;
use Vection\Contracts\Validator\ValidatorInterface;

/**
 * Class Validator
 *
 * @package Vection\Component\Validator\Validator
 */
class ValidationMediator implements ValidationMediatorInterface
{

    /**
     * An array that contains validator objects.
     * Contains the Vection\Component\Validator\Validator by default.
     *
     * @var ValidatorInterface[]
     */
    protected $validators;

    /**
     * ValidationAggregator constructor.
     */
    public function __construct()
    {
        # Add the default validator
        $this->addValidator(new GeneralValidator());
    }

    /**
     * Returns a new instance of AssertionChain.
     *
     * @return ValidationChainInterface
     */
    public function createValidationChain(): ValidationChainInterface
    {
        return new ValidationChain();
    }

    /**
     * Adds a specific validator object.
     *
     * @param ValidatorInterface $validator
     */
    public function addValidator(ValidatorInterface $validator): void
    {
        $this->validators[\get_class($validator)] = $validator;
    }

    /**
     * Executes the validator to verify the data against the
     * definition chain. If the data contains one or more
     * invalid values, this method throws an exception which
     * contains all particular exceptions.
     *
     * @param ValidationChainInterface $validationChain
     * @param array                    $data
     *
     * @throws ValidationChainFailedException
     */
    public function verify(ValidationChainInterface $validationChain, array $data): void
    {
        $validationFailedExceptions = [];

        foreach ( $validationChain->getDeferredValidation() as $name => $validation ) {

            # Get the value which will be validate
            $value = \array_key_exists($name, $data) ? $data[$name] : null;

            # Skip if value is null and validate against nullable
            if( $value === null && \in_array('nullable', \array_column($validation[GeneralValidator::class], 0)) ){
                continue;
            }

            foreach($validation as $validatorClass => $constraints){

                if( ! isset( $this->validators[$validatorClass] ) ){
                    try {
                        $reflection = new \ReflectionClass($validatorClass);
                        if( ! $reflection->implementsInterface(ValidatorInterface::class) ){
                            throw new \RuntimeException(
                                "Vection-Validator: Invalid class type for {$validatorClass}. Except an class
                                from type ".ValidatorInterface::class."."
                            );
                        }
                    } catch( \ReflectionException $e ) {
                        throw new \RuntimeException(
                            "Vection-Validator: Validator class not found: {$validatorClass}"
                        );
                    }

                    $this->validators[$validatorClass] = new $validatorClass();
                }

                try{
                    foreach( $constraints as $constraint ){
                        $this->validators[$validatorClass]->validate($value, $constraint);
                    }
                } catch( ValidationFailedExceptionInterface $e ) {
                    $validationFailedExceptions[] = $e;
                }

            }

        }

        if ( $validationFailedExceptions ) {
            throw new ValidationChainFailedException($validationFailedExceptions);
        }
    }
}