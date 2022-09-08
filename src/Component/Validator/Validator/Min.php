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
 * Class Min
 *
 * @package Vection\Component\Validator\Validator
 */
class Min extends Validator
{
    protected int|float $min;

    public function __construct(int|float $min)
    {
        $this->min = $min;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['min' => $this->min];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is to low, allowed is at least {min}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_int($value) && !is_float($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "int" or "float", but type "%s" was passed.', gettype($value))
            );
        }

        return $value >= $this->min;
    }
}
