<?php

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Property\Traits;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;

/**
 * Trait StringPropertyTrait
 *
 * @package Vection\Component\Validator\Schema\Property\Traits
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
trait IntegerPropertyTrait
{
    protected ?int $integerMax = null;
    protected ?int $integerMin = null;

    /**
     * @param array $schema
     *
     * @return void
     */
    protected function evaluateIntegerProperty(array $schema): void
    {
        if (isset($schema['@range'])) {
            $this->integerMax = $schema['@range']['max'] === 'php' ? PHP_INT_MAX : (int) $schema['@range']['max'];
            $this->integerMin = $schema['@range']['min'] === 'php' ? PHP_INT_MIN : (int) $schema['@range']['min'];
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
    public function validateIntegerProperty($value): void
    {
        if (!is_int($value)) {
            throw new IllegalPropertyTypeException($this->name, 'integer');
        }
        if (
            $this->integerMin !== null &&
            $this->integerMax !== null &&
            ($value < $this->integerMin || $value > $this->integerMax)
        ){
            throw new IllegalPropertyValueException($this->name, $this->integerMin.'-'.$this->integerMax);
        }
    }
}
