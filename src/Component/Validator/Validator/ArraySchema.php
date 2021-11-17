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

use Vection\Component\Validator\Schema\Exception\SchemaException;
use Vection\Component\Validator\Schema\Schema;
use Vection\Component\Validator\Schema\SchemaValidator;
use Vection\Component\Validator\Validator;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class ArraySchema extends Validator
{
    protected int                        $maxTemplateRecursion;
    protected PropertyExceptionInterface $propertyException;
    protected Schema                     $schema;

    /**
     * @param array $schema
     */
    public function __construct(array $schema, int $maxTemplateRecursion = 3)
    {
        $this->maxTemplateRecursion = $maxTemplateRecursion;

        $this->schema = new Schema();
        $this->schema->setSchema($schema);
        $this->schema->setMaxTemplateRecursion($this->maxTemplateRecursion);
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['schema' => $this->schema, 'maxTemplateRecursion' => $this->maxTemplateRecursion];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return $this->propertyException->getMessage();
    }

    /**
     * @inheritDoc
     *
     * @throws SchemaException
     */
    protected function onValidate($value): bool
    {
        if (!is_array($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "array", but type "%s" was passed.', gettype($value))
            );
        }

        try {
            (new SchemaValidator($this->schema))->validateArray($value);
            return true;
        }
        catch (PropertyExceptionInterface $e) {
            $this->propertyException = $e;
            return false;
        }
    }
}
