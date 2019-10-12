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
    protected $values = [];

    /**
     * @var Property
     */
    protected $member;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        foreach( $schema['@properties'] ?? [] as $name => $value ){
            $value = is_string($value) ? ['@type' => $value] : $value;
            $type = $this->createProperty($value['@type'], $name);
            $type->evaluate($value);
            $this->values[$name] = $type;
        }

        if( isset($schema['@property']) ){
            if( is_string($schema['@property']) ){
                $schema['@property'] = ['@type' => $schema['@property']];
            }
            $type = $this->createProperty($schema['@property']['@type']);
            $type->evaluate($schema['@property']);
            $this->member = $type;
        }
    }

    /**
     * @inheritDoc
     */
    public function onValidate($values): void
    {
        if( $this->required === true && count($values) === 0 ){
            throw new MissingPropertyException($this->name.'.*');
        }

        if( ! is_array($values) ){
            throw new IllegalPropertyTypeException($this->name, 'object');
        }

        foreach( $this->values as $type ){

            if( ! isset($values[$type->getName()]) ){
                if( ! $type->isRequired() ){
                    continue;
                }
                throw new MissingPropertyException($type->getName());
            }

            try{
                $type->validate($values[$type->getName()]);
            }
            catch(PropertyExceptionInterface $e){
                $e->withProperty($this->name);
            }
        }

        if( count($this->values) > 0 ){
            foreach( $values as $name => $value ){
                if( ! isset($this->values[$name]) ){
                    throw new IllegalPropertyException($this->name.'.'.$name, 'The property %s is not allowed.');
                }
            }
        }

        if( $this->member !== null ){
            foreach( $values as $name => $value ) {
                $this->member->setName($name);
                try{
                    $this->member->validate($value);
                }
                catch(PropertyExceptionInterface $e){
                    $e->withProperty($this->name);
                }
            }
        }

    }

}