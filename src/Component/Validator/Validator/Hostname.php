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
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class Hostname
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Hostname extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid hostname.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_string($value)) {
            throw new IllegalTypeException(sprintf(
                'The value must be of type "%s", but type "%s" was passed.',
                'string',
                gettype($value)
            ));
        }

        // The maximum length of a hostname is 255 characters.
        if (strlen($value) > 255) {
            return false;
        }

        $levels = explode('.', $value);

        // Probably several consecutive points.
        if (in_array('', $levels, true)) {
            return false;
        }

        foreach ($levels as $level) {
            if ((new Subdomain())->validate($level) !== null) {
                return false;
            }
        }

        return true;
    }
}
