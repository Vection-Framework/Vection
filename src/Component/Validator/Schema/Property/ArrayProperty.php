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
use Vection\Component\Validator\Schema\Exception\MissingPropertyException;
use Vection\Component\Validator\Schema\Property;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;

/**
 * Class ArrayProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 *
 * @author David Lung <vection@davidlung.de>
 */
class ArrayProperty extends Property
{

    /**
     * @var Property
     */
    protected $property;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        if ( isset($schema['@property']['@template']) ) {
            $schema['@property'] = $this->getTemplate($schema['@property']['@template']);
            unset($schema['@property']['@template']);
        }

        $this->property = $this->createProperty($schema['@property']['@type']);
        $this->property->evaluate($schema['@property']);
    }

    /**
     * @inheritDoc
     *
     * @throws MissingPropertyException
     */
    public function onValidate($values): void
    {
        if ( ! is_array($values) ) {
            throw new IllegalPropertyTypeException($this->name, 'array');
        }

        if ( $this->isRequired() && count($values) === 0 ) {
            throw new MissingPropertyException($this->name.'.0');
        }

        foreach ( $values as $name => $value ) {

            $this->property->setName((string) $name);

            try {
                $this->property->validate($value);
            } catch (PropertyExceptionInterface $e) {
                $e->withProperty($this->name);
            }

        }
    }
}
