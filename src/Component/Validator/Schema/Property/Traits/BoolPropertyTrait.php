<?php

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Property\Traits;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;

/**
 * Trait BoolPropertyTrait
 *
 * @package Vection\Component\Validator\Schema\Property\Traits
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
trait BoolPropertyTrait
{
    /**
     * @param array $schema
     *
     * @return void
     */
    protected function evaluateBoolProperty(array $schema): void
    {
        // Not used.
    }

    /**
     * @param $value
     *
     * @return void
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function validateBoolProperty($value): void
    {
        if (!is_bool($value)) {
            throw new IllegalPropertyTypeException($this->name, 'boolean');
        }
    }
}
