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
 * Class EndsLengthWith
 *
 * @package Vection\Component\Validator\Validator
 */
class Length extends Validator
{
    /** @var int */
    protected $length;

    /**
     * Length constructor.
     *
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
        return 'Value length "{value}" does not the length of {length}.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return strlen($value) === $this->length;
    }
}