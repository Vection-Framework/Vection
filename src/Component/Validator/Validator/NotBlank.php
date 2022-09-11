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

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class NotBlank
 *
 * @package Vection\Component\Validator\Validator
 */
class NotBlank extends Validator
{
    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is blank, but was expected to contain a value.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (!is_string($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "string", but type "%s" was passed.', gettype($value))
            );
        }

        return trim($value) !== '';
    }
}
