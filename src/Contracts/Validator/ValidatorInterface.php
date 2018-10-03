<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 *  (c) Vection <project@vection.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ValidatorInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidatorInterface
{

    /**
     * Validates the given value by considering
     * the constraints specified by the validator.
     *
     * @param mixed $value
     * @param array $constraints
     *
     * @throws ValidationFailedExceptionInterface
     */
    public function validate($value, array $constraints): void;

}