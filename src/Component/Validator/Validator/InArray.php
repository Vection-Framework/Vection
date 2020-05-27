<?php
/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;


use Vection\Component\Validator\Validator;

/**
 * Class InArray validate that a value is in array of values
 *
 * @package Vection\Component\Validator\Validator
 *
 * @author Bjoern Klemm <bjoern.klemm@appsdock.de>
 */
class InArray extends Validator
{
    /** @var array */
    protected $values;

    /**
     * Max constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }
    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return \in_array($value, $this->values, true);
    }

    /**
     * @inheritDoc
     */
    protected function getMessage(): string
    {
        return 'Value "{value}" is not available in array "{values}".';
    }
}