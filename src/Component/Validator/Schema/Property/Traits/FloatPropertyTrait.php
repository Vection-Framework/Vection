<?php

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Property\Traits;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;

/**
 * Trait FloatPropertyTrait
 *
 * @package Vection\Component\Validator\Schema\Property\Traits
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
trait FloatPropertyTrait
{
    protected ?float $floatMax = null;
    protected ?float $floatMin = null;

    /**
     * @param array $schema
     *
     * @return void
     */
    protected function evaluateFloatProperty(array $schema): void
    {
        if (isset($schema['@range'])) {
            $this->floatMax = $schema['@range']['max'] === 'php' ? PHP_FLOAT_MAX : (float) $schema['@range']['max'];
            $this->floatMin = $schema['@range']['min'] === 'php' ? PHP_FLOAT_MIN : (float) $schema['@range']['min'];
        }
    }

    /**
     * @param $value
     *
     * @return void
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function validateFloatProperty($value): void
    {
        if (!is_float($value)) {
            throw new IllegalPropertyTypeException($this->name, 'integer');
        }
        if ($this->floatMin !== null && $this->floatMax !== null && ($value < $this->floatMin || $value > $this->floatMax)) {
            throw new IllegalPropertyValueException($this->name, $this->floatMin.'-'.$this->floatMax);
        }
    }
}
