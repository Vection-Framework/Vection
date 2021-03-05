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
 * Class Locale
 *
 * @package Vection\Component\Validator\Validator
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Locale extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid locale.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return preg_match('/^[A-Za-z]{2,4}([_-][A-Za-z]{4})?([_-]([A-Za-z]{2}|[\d]{3}))?$/', $value) === 1;
    }
}
