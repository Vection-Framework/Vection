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

/*
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
 * Class Max
 *
 * @package Vection\Component\Validator\Validator
 */
class Max extends Validator
{

    /** @var integer */
    protected $max;

    /**
     * Max constructor.
     *
     * @param int $max
     *
     */
    public function __construct(int $max)
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
        return $value <= $this->max;
    }
}
