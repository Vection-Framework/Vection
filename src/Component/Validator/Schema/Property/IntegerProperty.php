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

declare(strict_types = 1);

namespace Vection\Component\Validator\Schema\Property;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;
use Vection\Component\Validator\Schema\Property;

/**
 * Class IntegerProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 *
 * @author David Lung <vection@davidlung.de>
 */
class IntegerProperty extends Property
{
    /**
     * @var array
     */
    protected $range = [];

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        if( isset($schema['@range']) ){
            $this->range = explode('..', $schema['@range']);
        }
    }

    /**
     * @inheritDoc
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function onValidate($value): void
    {
        if( ! is_int($value) ){
            throw new IllegalPropertyTypeException($this->name, 'integer');
        }

        if( count($this->range) > 0 && ($value < $this->range[0] || $value > $this->range[1]) ){
            throw new IllegalPropertyValueException($this->name, implode('-', $this->range));
        }
    }
}