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
 * Class BetweenLength
 *
 * @package Vection\Component\Validator\Validator
 */
class BetweenLength extends Validator
{

    /** @var integer */
    protected $min;

    /** @var integer */
    protected $max;

    /**
     * BetweenValue constructor.
     *
     * @param int    $min
     * @param int    $max
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
        return 'Length of "{value}" is neither greater than or equal to {min} nor less than or equal to {max}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $length = strlen($value);
        return $length <= $this->max && $length >= $this->min;
    }
}
