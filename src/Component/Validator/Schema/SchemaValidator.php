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

namespace Vection\Component\Validator\Schema;

use RuntimeException;
use Vection\Contracts\Validator\Schema\SchemaInterface;
use Vection\Contracts\Validator\Schema\SchemaValidatorInterface;

/**
 * Class SchemaValidator
 *
 * @package Vection\Component\Validator\Schema
 *
 * @author David Lung <vection@davidlung.de>
 */
class SchemaValidator implements SchemaValidatorInterface
{

    /**
     * @var SchemaInterface
     */
    protected $schema;

    /**
     * SchemaValidator constructor.
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
        if ( ! file_exists($path) ) {
            throw new RuntimeException('File not exists: '.$path);
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);

        $content = file_get_contents($path);

        try {
            if ( in_array($ext, ['yaml', 'yml']) ) {
                return $this->validateYamlString($content);
            }

            if ( $ext === 'json' ) {
                return $this->validateJsonString($content);
            }
        } catch (\RuntimeException $e) {
            throw new RuntimeException('Unable to validate file '.$path, 0, $e);
        }

        throw new RuntimeException('Schema validator: Expects json or yaml, got unsupported file: '.$path.'');
    }

    /**
     * @inheritDoc
     */
    public function validateJsonString(string $json): array
    {
        $data = json_decode($json, true);

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new RuntimeException('JSON '.json_last_error_msg());
        }

        $this->validateArray($data);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function validateYamlString(string $yaml): array
    {
        if ( ! function_exists('yaml_parse') ) {
            throw new RuntimeException('Schema validation for yaml files require the php yaml extension.');
        }

        $data = yaml_parse($yaml);

        if ( $data === false || ! is_array($data) ) {
            throw new RuntimeException('Schema validation: Cannot parse yaml string - invalid yaml.');
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
