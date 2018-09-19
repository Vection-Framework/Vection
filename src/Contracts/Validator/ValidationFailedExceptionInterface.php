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

/**
 * Interface AbstractValidationFailedException
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationFailedExceptionInterface extends \Throwable
{
    /**
     * Returns the validated value.
     *
     * @return mixed
     */
    public function getValidatedValue();

    /**
     * Returns the constraints that were used to validate the value.
     *
     * @return array
     */
    public function getConstraints(): array;
}