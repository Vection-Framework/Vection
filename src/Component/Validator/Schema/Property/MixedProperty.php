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

namespace Vection\Component\Validator\Schema\Property;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;
use Vection\Component\Validator\Schema\Property;
use Vection\Component\Validator\Schema\Property\Traits\ArrayPropertyTrait;
use Vection\Component\Validator\Schema\Property\Traits\ObjectPropertyTrait;

/**
 * Class MixedProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 * @author  David Lung <vection@davidlung.de>
 */
class MixedProperty extends Property
{
    use ArrayPropertyTrait;
    use ObjectPropertyTrait;

    protected array      $allowed    = [];
    protected array      $properties = [];
    protected ? Property $property   = null;
    protected ? string   $regex      = null;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        if ( isset($schema['@allowed']) ) {
            $this->allowed = explode('|', $schema['@allowed']);
        }

        $this->regex = ($schema['@regex'] ?? null);

        $this->evaluateArrayProperty($schema);
        $this->evaluateObjectProperty($schema);
    }

    /**
     * @inheritDoc
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function onValidate($value): void
    {
        if (
            ! is_string($value)  &&
            ! is_numeric($value) &&
            ! is_int($value)     &&
            ! is_float($value)   &&
            ! is_bool($value)    &&
            ! is_array($value)
        ) {
            throw new IllegalPropertyTypeException($this->name, 'mixed');
        }

        if ( $this->regex !== null && ! preg_match($this->regex, $value) ) {
            throw new IllegalPropertyValueException($this->name, $this->regex);
        }

        if ( count($this->allowed) > 0 && ! in_array($value, $this->allowed, true) ) {
            throw new IllegalPropertyValueException($this->name, implode('|', $this->allowed));
        }

        if ( is_array($value)) {
            if ( array_keys($value) !== range(0, count($value) - 1) ) {
                $this->validateObjectProperty($value);
            }
            else {
                $this->validateArrayProperty($value);
            }
        }
    }
}
