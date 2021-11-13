<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator\Validator;

use InvalidArgumentException;
use Vection\Component\Validator\Schema\Schema;
use Vection\Component\Validator\Schema\SchemaValidator;
use Vection\Component\Validator\Validator;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class ArraySchema extends Validator
{
    protected InvalidArgumentException   $invalidArgumentException;
    protected PropertyExceptionInterface $propertyException;
    protected Schema                     $schema;
    protected SchemaExceptionInterface   $schemaException;

    /**
     * @param array $schema
     */
    public function __construct(array $schema)
    {
        $this->schema = new Schema();
        $this->schema->setSchema($schema);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        if ($this->invalidArgumentException) {
            return $this->invalidArgumentException->getMessage();
        }
        if ($this->schemaException) {
            return 'The schema is flawed. '.$this->schemaException->getMessage();
        }
        return 'Value "{value}" does not correspond to the required scheme. '.$this->propertyException->getMessage();
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException(
                sprintf('The argument must be of type "array", but type "%s" given.', gettype($value))
            );
        }

        try {
            (new SchemaValidator($this->schema))->validateArray($value);
            return true;
        }
        catch (PropertyExceptionInterface $e) {
            $this->propertyException = $e;
        }
        catch (SchemaExceptionInterface $e) {
            $this->schemaException = $e;
        }
        return false;
    }
}
