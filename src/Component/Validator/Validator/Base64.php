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

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class Base64
 *
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Base64 extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid base64 encoded value.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return ($decoded = base64_decode($value, true)) !== false && base64_encode($decoded) === $value;
    }
}
