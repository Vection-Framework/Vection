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

namespace Vection\Component\Validator;

use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Contracts\Validator\ValidatorInterface;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class Validator
 *
 * This class is an abstract validator that determines the
 * correctness of a given value by using specific rules and
 * dynamic constraints.
 *
 * @package Vection\Component\Validator
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Validator implements ValidatorInterface
{
    protected string $message = '';

    /**
     * @param mixed $value
     *
     * @return bool
     *
     * @throws IllegalTypeException
     */
    abstract protected function onValidate(mixed $value): bool;

    /**
     * Returns an array of the validator implementation specific constraints
     * which are used to validate the given value. This returned array
     * should use the constraint names as key.
     *
     * E.g. constraints: min and max:
     * ['min' => $min, 'max' => $max]
     *
     * @return mixed[]
     */
    protected function getConstraints(): array
    {
        return [];
    }

    /**
     * Returns a message which will be display when the validation
     * failed. The message can contain the constraint names in curly brackets
     * that will be replaced with the values from constraints get by getConstraints method.
     *
     * E.g. "Value {value} does not match the given format {format}."
     *
     * By default, you can use the {value} token to place the current value.
     *
     * @return string
     */
    abstract protected function getMessage(): string;

    /**
     * Sets an alternative message to display if validation failed.
     * The use of this method will override the default message.
     *
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, string $subject = ''): ViolationInterface|null
    {
        try {
            if ($this->onValidate($value)) {
                return null;
            }

            $message = $this->message ?: $this->getMessage();
        }
        catch (IllegalTypeException $e) {
            $message = $e->getMessage();
        }

        return new Violation($subject, $value, $this->getConstraints(), $message);
    }
}
