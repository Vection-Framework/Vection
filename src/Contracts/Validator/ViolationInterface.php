<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ViolationInterface
 *
 * This interface represents a violation of rules defined by a validator.
 * Every time a validator failed validation, an object of this class
 * will be created an returned. This class contains the information
 * about the invalid value and an user message.
 *
 * @package Vection\Contracts\Validator
 */
interface ViolationInterface
{
    /**
     * Returns the invalid value that does not match the rules
     * of the used validator.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Returns an user message that contains the value and constraints
     * that were used to validate the value.
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Returns the message
     *
     * @see ViolationInterface::getMessage()
     *
     * @return string
     */
    public function __toString(): string;
}