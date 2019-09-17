<?php declare(strict_types=1);

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
 * This interface defines a validator that determines the
 * correctness of an given value by using specific rules and
 * dynamic constraints.
 *
 * @package Vection\Contracts\Validator
 */
interface ValidatorInterface
{
    /**
     * Validates the given value.
     * Returns null if the validations succeeded. If the value is invalid,
     * this method returns an object of ViolationInterface that contains
     * the invalid value and an user message.
     *
     * @param mixed $value
     *
     * @return ViolationInterface|null
     */
    public function validate($value): ? ViolationInterface;

}