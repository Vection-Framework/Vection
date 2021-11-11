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
 * Class IsArray
 *
 * @package Vection\Component\Validator\Validator
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class IsArray extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not an array.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return is_array($value);
    }
}
