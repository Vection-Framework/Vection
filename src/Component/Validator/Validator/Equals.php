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
 * Class Equals
 *
 * @package Vection\Component\Validator\Validator
 */
class Equals extends Validator
{
    /** @var string */
    protected $equalValue;

    /**
     * Equals constructor.
     *
     * @param mixed $equalValue
     */
    public function __construct($equalValue)
    {
        $this->equalValue = $equalValue;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['equalValue' => $this->equalValue];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not equal to value "{equalValue}".';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return $value === $this->equalValue;
    }
}