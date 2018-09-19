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

namespace Vection\Contracts\Validator;


interface ValidatorInterface
{
    /**
     * Returns a new instance of AssertionChain.
     *
     * @return ValidationChainInterface
     */
    public function createValidationChain(): ValidationChainInterface;

    /**
     * Executes the validator to verify the data against the
     * definition chain. If the data contains one or more
     * invalid values, this method throws an exception which
     * contains all particular exceptions.
     *
     * @param ValidationChainInterface $assertionChain
     * @param array $data
     *
     * @throws ValidationChainFailedExceptionInterface
     */
    public function verify(ValidationChainInterface $assertionChain, array $data): void;
}