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

namespace Vection\Component\Validator;

use Vection\Contracts\Validator\ValidatorInterface;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class Validator
 *
 * This class is an abstract validator that determines the
 * correctness of an given value by using specific rules and
 * dynamic constraints.
 *
 * @package Vection\Component\Validator
 */
abstract class Validator implements ValidatorInterface
{
    /** @var string */
    protected $message;

    /**
     * @param $value
     *
     * @return bool
     */
    abstract protected function onValidate($value): bool;

    /**
     * Returns an array of the validator implementation specific constraints
     * which are used to validate the given value. This returned array
     * should using the constraint names as key.
     *
     * E.g. constraints: min and max:
     * ['min' => $min, 'max' => $max]
     *
     * @return array
     */
    abstract protected function getConstraints(): array;

    /**
     * Returns an message which will be display when the validation
     * failed. The message can contains the constraint names in curly brackets
     * that will be replaced with the values from constraints get by getConstraints method.
     *
     * E.g. "Value {value} does not match the given format {format}.
     *
     * By default you can use the {value} token to place the current value.
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
    public function validate($value): ? ViolationInterface
    {
        if( $a = $this->onValidate($value) ){
            return null;
        }

        return new Violation($value, $this->getConstraints(), $this->message ?: $this->getMessage());
    }
}