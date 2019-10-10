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

namespace Vection\Component\Validator\Schema\Json\Property;

use Vection\Component\Validator\Schema\Json\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Json\Exception\IllegalPropertyValueException;
use Vection\Component\Validator\Schema\Json\JsonProperty;

/**
 * Class JsonInteger
 *
 * @package Vection\Component\Validator\Schema\Json\Property
 *
 * @author David Lung <vection@davidlung.de>
 */
class JsonInteger extends JsonProperty
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
    public function validate($value): void
    {
        if( ! is_int($value) ){
            throw new IllegalPropertyTypeException($this->name, 'integer');
        }

        if( count($this->range) > 0 && ($value < $this->range[0] || $value > $this->range[1]) ){
            throw new IllegalPropertyValueException($this->name, implode('-', $this->range));
        }
    }
}