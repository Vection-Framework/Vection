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
use Vection\Component\Validator\Schema\Property\Traits\BoolPropertyTrait;
use Vection\Component\Validator\Schema\Property\Traits\FloatPropertyTrait;
use Vection\Component\Validator\Schema\Property\Traits\IntegerPropertyTrait;
use Vection\Component\Validator\Schema\Property\Traits\ObjectPropertyTrait;
use Vection\Component\Validator\Schema\Property\Traits\StringPropertyTrait;

/**
 * Class MixedProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 * @author  David Lung <vection@davidlung.de>
 */
class MixedProperty extends Property
{
    use ArrayPropertyTrait;
    use BoolPropertyTrait;
    use FloatPropertyTrait;
    use IntegerPropertyTrait;
    use ObjectPropertyTrait;
    use StringPropertyTrait;

    protected array     $allowed    = [];
    protected array     $properties = [];
    protected ?Property $property   = null;
    protected ?string   $regex      = null;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        $this->evaluateBoolProperty($schema);
        $this->evaluateFloatProperty($schema);
        $this->evaluateIntegerProperty($schema);
        $this->evaluateStringProperty($schema);

        if (isset($schema['@property'])) {
            $this->evaluateArrayProperty($schema);
        }
        if (isset($schema['@property']) || isset($schema['@properties'])) {
            $this->evaluateObjectProperty($schema);
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
        if (
            !is_array($value)   &&
            !is_bool($value)    &&
            !is_float($value)   &&
            !is_int($value)     &&
            !is_string($value)
        ) {
            throw new IllegalPropertyTypeException($this->name, 'mixed');
        }

        if (is_array($value)) {
            if (array_keys($value) !== range(0, count($value) - 1)) {
                $this->validateObjectProperty($value);
            }
            else {
                $this->validateArrayProperty($value);
            }
        }
        if (is_bool($value)) {
            $this->validateBoolProperty($value);
        }
        if (is_float($value)) {
            $this->validateFloatProperty($value);
        }
        if (is_int($value)) {
            $this->validateIntegerProperty($value);
        }
        if (is_string($value)) {
            $this->validateStringProperty($value);
        }
    }
}
