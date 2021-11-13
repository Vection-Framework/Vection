<?php
/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Contracts\Validator;

/**
 * Interface ValidatorInterface
 *
 * This interface defines a validator that determines the
 * correctness of a given value by using specific rules and
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
     * the invalid value and a user message.
     *
     * @param mixed  $value
     * @param string $subject
     *
     * @return ViolationInterface|null
     */
    public function validate($value, string $subject = ''): ? ViolationInterface;
}
