<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return str_contains($value, 'xxx');
    }

    /**
     * Returns an message which will be display when the validation
     * failed. The message can contains the constraint names in curly brackets
     * that will be replaced with the values from constraints get by getConstraints method.
     *
     * E.g. "Value {value} does not match the given format {format}.
     *
     * By default, you can use the {value} token to place the current value.
     *
     * @return string
     */
    protected function getMessage(): string
    {
        return 'Value {value} does not contains the string "xxx"';
    }
}
