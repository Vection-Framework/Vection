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

declare(strict_types = 1);

namespace Vection\Component\Validator\Schema\Json;

use RuntimeException;
use Vection\Contracts\Validator\Schema\Json\JsonSchemaValidatorInterface;
use Vection\Contracts\Validator\Schema\Json\SchemaInterface;

/**
 * Class JsonSchemaValidator
 *
 * @package Vection\Component\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
class JsonSchemaValidator implements JsonSchemaValidatorInterface
{
    /**
     * @var SchemaInterface
     */
    protected $schema;

    /**
     * JsonSchemaValidator constructor.
     *
     * @param SchemaInterface $schema
     */
    public function __construct(SchemaInterface $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @inheritDoc
     */
    public function validate(string $path): array
    {
        if( ! file_exists($path) ){
            throw new RuntimeException('File not exists: '.$path);
        }

        $json = file_get_contents($path);
        $this->validateString($json);
    }

    /**
     * @inheritDoc
     */
    public function validateString(string $json): array
    {
        $data = json_decode($json, true);

        if( json_last_error() !== JSON_ERROR_NONE ) {
            throw new RuntimeException(json_last_error_msg());
        }

        $this->validateArray($data);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function validateArray(array $data): void
    {
        $this->schema->evaluate()->validate($data);
    }
}