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

use Vection\Component\Validator\Schema\Schema;
use Vection\Component\Validator\Schema\SchemaValidator;
use Vection\Component\Validator\Validator;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class ArraySchema extends Validator
{
    protected Schema $schema;

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
        return 'Value "{value}" does not correspond to the required scheme.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        try {
            (new SchemaValidator($this->schema))->validateArray($value);
            return true;
        }
        catch (PropertyExceptionInterface $e) {
            return false;
        }
    }
}
