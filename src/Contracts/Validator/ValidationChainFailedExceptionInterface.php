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
 * Interface ValidationChainFailedExceptionInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationChainFailedExceptionInterface extends \Throwable
{
    /**
     * Returns all validation failed exceptions for a set of data.
     *
     * @return array
     */
    public function getValidationFailedExceptions(): array;
}