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
 * Interface ValidatableInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidatableInterface
{
    /**
     * Defines constraints for a set of data.
     *
     * @param ValidationChainInterface $chain
     */
    public function defineValidation(ValidationChainInterface $chain): void;
}