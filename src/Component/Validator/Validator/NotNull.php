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

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class NotNull
 *
 * @package Vection\Component\Validator\Validator
 */
class NotNull extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Expected a value but got null.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value !== null;
    }
}
