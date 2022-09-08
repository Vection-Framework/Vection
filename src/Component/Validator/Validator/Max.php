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
 * Class Max
 *
 * @package Vection\Component\Validator\Validator
 */
class Max extends Validator
{
    protected int|float $max;

    /**
     * @param int|float $max
     */
    public function __construct(int|float $max)
    {
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['max' => $this->max];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is to height, allowed is only a maximum of {max}.';
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

        return $value <= $this->max;
    }
}
