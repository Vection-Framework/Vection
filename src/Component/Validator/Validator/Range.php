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

/**
 * Class Range
 *
 * @package Vection\Component\Validator\Validator
 */
class Range extends Validator
{
    protected int $min;
    protected int $max;

    /**
     * @param int $min
     * @param int $max
     */
    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['min' => $this->min, 'max' => $this->max];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not in range of {min} and {max}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value <= $this->max && $value >= $this->min;
    }
}
