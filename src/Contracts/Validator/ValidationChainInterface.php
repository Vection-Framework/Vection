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
 * Interface ValidationChainInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationChainInterface
{

    /**
     * Returns all defined assertions.
     *
     * @return array
     */
    public function getDeferredValidation(): array;

    /**
     * @param string $validatorClassName
     * @param array $constraints
     *
     * @return ValidationChainInterface
     */
    public function validate(string $validatorClassName, array $constraints = []): ValidationChainInterface;

    /**
     * Adds a key for which all following
     * assertion will be set.
     *
     * @param string $name
     *
     * @return ValidationChainInterface
     */
    public function __invoke(string $name): ValidationChainInterface;

    /**
     * This magic method is used to accept several validation
     * function which will be process later in the Validator.
     *
     * @param string $name
     * @param array $constraints
     *
     * @return ValidationChainInterface
     */
    public function __call($name, $constraints): ValidationChainInterface;
}