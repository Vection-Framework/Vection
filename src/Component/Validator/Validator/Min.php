<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class Min
 *
 * @package Vection\Component\Validator\Validator
 */
class Min extends Validator
{
    /** @var int */
    protected $min;

    /**
     * Min constructor.
     *
     * @param int $min
     */
    public function __construct(int $min)
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
        return $value >= $this->min;
    }
}