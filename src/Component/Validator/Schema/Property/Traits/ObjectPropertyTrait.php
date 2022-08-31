<?php

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Property\Traits;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\MissingPropertyException;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;

/**
 * Trait ObjectPropertyTrait
 *
 * @package Vection\Component\Validator\Schema\Property\Traits
 * @author  David Brucksch <david.brucksch@appsdock.de>
 */
trait ObjectPropertyTrait
{
    /**
     * @param mixed[] $schema
     *
     * @throws SchemaExceptionInterface
     */
    protected function evaluateObjectProperty(array $schema): void
    {
        foreach ( ($schema['@properties'] ?? []) as $name => $value ) {
            $value = is_string($value) ? ['@type' => $value] : $value;
            $property = $this->createProperty($name, $value['@type']);
            $property->evaluate($value);
            $this->properties[$name] = $property;
        }

        if ( isset($schema['@property']) ) {
            if ( is_string($schema['@property']) ) {
                $schema['@property'] = ['@type' => $schema['@property']];
            }
            $property = $this->createProperty('', $schema['@property']['@type']);
            $property->evaluate($schema['@property']);
            $this->property = $property;
        }
    }

    /**
     * @param mixed $values
     *
     * @throws IllegalPropertyException
     * @throws IllegalPropertyTypeException
     * @throws MissingPropertyException
     * @throws PropertyExceptionInterface
     */
    protected function validateObjectProperty(mixed $values): void
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
                throw new MissingPropertyException($this->name.'.'.$property->getName());
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
                $this->property->setName((string)$name);
                try {
                    $this->property->validate($value);
                } catch (PropertyExceptionInterface $e) {
                    $e->withProperty($this->name);
                }
            }
        }
    }
}
