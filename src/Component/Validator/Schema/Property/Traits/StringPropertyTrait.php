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
trait StringPropertyTrait
{
    protected array       $allowed   = [];
    protected int|null    $maxLength = null;
    protected int|null    $minLength = null;
    protected string|null $regex     = null;

    /**
     * @param array $schema
     *
     * @return void
     */
    protected function evaluateStringProperty(array $schema): void
    {
        if (isset($schema['@allowed'])) {
            $this->allowed = explode('|', $schema['@allowed']);
        }

        $this->maxLength = $schema['@maxLength'] ?? null;
        $this->minLength = $schema['@minLength'] ?? null;
        $this->regex     = $schema['@regex']     ?? null;
    }

    /**
     * @param $value
     *
     * @return void
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function validateStringProperty($value): void
    {
        if (!is_string($value)) {
            throw new IllegalPropertyTypeException($this->name, 'string');
        }
        if (count($this->allowed) && !in_array($value, $this->allowed, true)) {
            throw new IllegalPropertyValueException($this->name, implode('|', $this->allowed));
        }
        if ($this->maxLength && strlen($value) > $this->maxLength) {
            throw new IllegalPropertyValueException($this->name, (string) $this->maxLength);
        }
        if ($this->minLength && strlen($value) < $this->minLength) {
            throw new IllegalPropertyValueException($this->name, (string) $this->minLength);
        }
        if (is_string($this->regex) && !preg_match($this->regex, $value)) {
            throw new IllegalPropertyValueException($this->name, $this->regex);
        }
    }
}
