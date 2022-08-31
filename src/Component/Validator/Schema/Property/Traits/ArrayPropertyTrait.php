<?php

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Property\Traits;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\InvalidPropertyValueException;
use Vection\Component\Validator\Schema\Exception\MissingPropertyException;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;

/**
 * Trait ArrayPropertyTrait
 *
 * @package Vection\Component\Validator\Schema\Property\Traits
 * @author  David Brucksch <david.brucksch@appsdock.de>
 */
trait ArrayPropertyTrait
{
    use BoolPropertyTrait;
    use FloatPropertyTrait;
    use IntegerPropertyTrait;
    use StringPropertyTrait;

    protected int|null $maxArraySize = null;

    /**
     * @param mixed[] $schema
     *
     * @throws SchemaExceptionInterface
     */
    protected function evaluateArrayProperty(array $schema): void
    {
        if (isset($schema['@maxArraySize'])) {
            $this->maxArraySize = (int) $schema['@maxArraySize'];
        }

        $this->property = $this->createProperty('', $schema['@property']['@type']);
        $this->property->evaluate($schema['@property']);
    }

    /**
     * @param mixed $values
     *
     * @throws IllegalPropertyTypeException
     * @throws MissingPropertyException
     * @throws PropertyExceptionInterface
     */
    public function validateArrayProperty(mixed $values): void
    {
        if (!is_array($values)) {
            throw new IllegalPropertyTypeException($this->name, 'array');
        }

        if ($this->isRequired() && count($values) === 0) {
            throw new MissingPropertyException($this->name.'.0');
        }

        if ($this->maxArraySize && count($values) > $this->maxArraySize) {
            throw new InvalidPropertyValueException(
                $this->name,
                sprintf('The array is size exceeded. The max allowed size is "%s" elements.', $this->maxArraySize)
            );
        }

        foreach ($values as $name => $value) {
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

            $this->property->setName((string) $name);

            try {
                $this->property->validate($value);
            } catch (PropertyExceptionInterface $e) {
                $e->withProperty($this->name);
            }
        }
    }
}
