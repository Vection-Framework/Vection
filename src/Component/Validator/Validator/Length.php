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

/**
 * Class Length
 *
 * @package Vection\Component\Validator\Validator
 */
class Length extends Validator
{
    protected int $length;

    /**
     * @param int $length
     */
    public function __construct(int $length)
    {
        $this->length = $length;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['length' => $this->length];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'The length of "{value}" does not correspond to the required length of {length}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (is_countable($value)) {
            return count($value) === $this->length;
        }

        if (is_int($value) || is_float($value)) {
            $value = (string) $value;
        }

        if (!is_string($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "string", but type "%s" was passed.', gettype($value))
            );
        }

        return strlen($value) === $this->length;
    }
}
