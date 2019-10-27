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

use Vection\Component\Validator\Schema\Exception\IllegalPropertyException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\MissingPropertyException;
use Vection\Component\Validator\Schema\Property;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;

/**
 * Class ObjectProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 *
 * @author David Lung <vection@davidlung.de>
 */
class ObjectProperty extends Property
{

    /**
     * @var Property[]
     */
    protected $properties = [];

    /**
     * @var Property
     */
    protected $property;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        foreach ( ($schema['@properties'] ?? []) as $name => $value ) {
            $value    = is_string($value) ? ['@type' => $value] : $value;
            $property = $this->createProperty($value['@type'], $name);
            $property->evaluate($value);
            $this->properties[$name] = $property;
        }

        if ( isset($schema['@property']) ) {
            if ( is_string($schema['@property']) ) {
                $schema['@property'] = ['@type' => $schema['@property']];
            }
            $property = $this->createProperty($schema['@property']['@type']);
            $property->evaluate($schema['@property']);
            $this->property = $property;
        }
    }

    /**
     * @inheritDoc
     */
    public function onValidate($values): void
    {
        if ( $this->required === true && is_array($values) && count($values) === 0 ) {
            throw new MissingPropertyException($this->name.'.*');
        }

        if ( ! is_array($values) ) {
            throw new IllegalPropertyTypeException($this->name, 'object');
        }

        foreach ( $this->properties as $property ) {

            if ( ! isset($values[$property->getName()]) ) {
                if ( ! $property->isRequired() ) {
                    continue;
                }
                throw new MissingPropertyException($property->getName());
            }

            try {
                $property->validate($values[$property->getName()]);
            } catch (PropertyExceptionInterface $e) {
                $e->withProperty($this->name);
            }
        }

        if ( count($this->properties) > 0 ) {
            foreach ( $values as $name => $value ) {
                if ( ! isset($this->properties[$name]) ) {
                    throw new IllegalPropertyException($this->name.'.'.$name, 'The property %s is not allowed.');
                }
            }
        }

        if ( $this->property !== null ) {
            foreach ( $values as $name => $value ) {
                $this->property->setName($name);
                try {
                    $this->property->validate($value);
                } catch (PropertyExceptionInterface $e) {
                    $e->withProperty($this->name);
                }
            }
        }

    }

}
