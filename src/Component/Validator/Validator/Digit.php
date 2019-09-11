<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;


use Vection\Component\Validator\Validator;

/**
 * Class Digit
 *
 * @package Vection\Component\Validator\Validator
 */
class Digit extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a digit.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return ctype_digit($value);
    }
}