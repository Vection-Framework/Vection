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

namespace Vection\Component\Validator\Tests\Fixtures;

use Vection\Component\Validator\Validator;

/**
 * Class CustomValidator
 *
 * @package Vection\Component\Validator\Tests\Fixtures
 */
class CustomValidator extends Validator
{

    /**
     * @param $value
     *
     * @return bool
     */
    protected function onValidate($value): bool
    {
        return strpos($value, 'xxx') !== false;
    }

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
    protected function getConstraints(): array
    {
        return [];
    }

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
    protected function getMessage(): string
    {
        return 'Value {value} does not contains the string "xxx"';
    }
}