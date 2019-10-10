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
use Vection\Component\Validator\Schema\Json\Exception\MissingPropertyException;
use Vection\Component\Validator\Schema\Json\JsonProperty;

/**
 * Class JsonString
 *
 * @package Vection\Component\Validator\Schema\Json\Property
 *
 * @author  David Lung <vection@davidlung.de>
 */
class JsonString extends JsonProperty
{
    /**
     * @var array
     */
    protected $allowed = [];

    /**
     * @var string
     */
    protected $regex;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        if( isset($schema['@allowed']) ){
            $this->allowed = explode('|', $schema['@allowed']);
        }

        $this->regex = $schema['@regex'] ?? null;
    }

    /**
     * @inheritDoc
     *
     * @throws MissingPropertyException
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function validate($value): void
    {
        if( $value === null && $this->required === true ){
            throw new MissingPropertyException($this->name);
        }

        if( ! is_string($value) ){
            throw new IllegalPropertyTypeException($this->name, 'string');
        }

        if( $this->regex !== null && ! preg_match($this->regex, $value) ){
            throw new IllegalPropertyValueException($this->name, $this->regex);
        }

        if( count($this->allowed) > 0 && ! in_array($value, $this->allowed, true) ){
            throw new IllegalPropertyValueException($this->name, implode('|', $this->allowed));
        }
    }
}