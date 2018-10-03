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

namespace Vection\Contracts\Validator;

/**
 * Interface ValidationMediatorInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationMediatorInterface
{
    /**
     * Returns a new instance of AssertionChain.
     *
     * @return ValidationChainInterface
     */
    public function createValidationChain(): ValidationChainInterface;

    /**
     * Adds a specific validator object.
     *
     * @param ValidatorInterface $validator
     */
    public function addValidator(ValidatorInterface $validator): void;

    /**
     * Executes the validators to verify the data against the
     * definition chain. If the data contains one or more
     * invalid values, this method throws an exception which
     * contains all particular exceptions.
     *
     * @param ValidationChainInterface $validationChain
     * @param array $data
     *
     * @throws ValidationChainFailedExceptionInterface
     */
    public function verify(ValidationChainInterface $validationChain, array $data): void;
}