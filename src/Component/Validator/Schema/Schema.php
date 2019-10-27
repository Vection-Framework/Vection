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
use Vection\Component\Validator\Schema\Property\ObjectProperty;
use Vection\Contracts\Validator\Schema\PropertyInterface;
use Vection\Contracts\Validator\Schema\SchemaInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Validator\Schema
 *
 * @author David Lung <vection@davidlung.de>
 */
class Schema implements SchemaInterface
{

    /**
     * @var array
     */
    protected $schema = [];

    /**
     * @var array
     */
    protected $templates = [];

    /**
     * Schema constructor.
     *
     * @param string|null $schemaFilePath
     */
    public function __construct(string $schemaFilePath = null)
    {
        if ( $schemaFilePath !== null ) {

            if ( ! file_exists($schemaFilePath) ) {
                throw new RuntimeException(
                    "Schema: Cannot load schema from file {$schemaFilePath}, file not exits."
                );
            }

            $ext = pathinfo($schemaFilePath, PATHINFO_EXTENSION);

            if ( in_array($ext, ['yaml', 'yml']) ) {
                $this->loadFromJsonFile($schemaFilePath);
            }

            if ( $ext === 'json' ) {
                $this->loadFromJsonFile($schemaFilePath);
            }
        }
    }

    /**
     * @param string $path
     */
    public function loadFromYamlFile(string $path): void
    {
        if ( ! file_exists($path) ) {
            throw new RuntimeException("Yaml Schema: Cannot load schema from file {$path}, file not exits.");
        }

        if ( ! function_exists('yaml_parse_file') ) {
            throw new RuntimeException('Yaml schemas require the php yaml extension.');
        }

        $schema = yaml_parse_file($path);

        if ( $schema === false ) {
            throw new RuntimeException("Unable to load schema from file {$path}");
        }

        $this->setSchema($schema);
    }

    /**
     * @param string $path
     */
    public function loadFromJsonFile(string $path): void
    {
        if ( ! file_exists($path) ) {
            throw new RuntimeException("Json Schema: Cannot load schema from file {$path}, file not exits.");
        }

        $schema = file_get_contents($path);

        if ( $schema === false ) {
            throw new RuntimeException("Unable to load schema from file {$path}");
        }

        $schema = json_decode($schema, true);

        if ( json_last_error() !== JSON_ERROR_NONE ) {
            throw new RuntimeException('Invalid json schema file: '.json_last_error_msg());
        }

        $this->setSchema($schema);
    }

    /**
     * @param array $schema
     */
    public function setSchema(array $schema): void
    {
        if ( isset($schema['@templates']) ) {
            $this->templates = $schema['@templates'];
            unset($schema['@templates']);
        }

        $this->schema = $schema;
    }

    /**
     * @inheritDoc
     */
    public function addTemplate(string $name, array $schema): void
    {
        $this->templates[$name] = $schema;
    }

    /**
     * @param array $templates
     */
    public function setTemplates(array $templates): void
    {
        $this->templates = $templates;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(): PropertyInterface
    {
        $root = new ObjectProperty('$', $this->templates);
        $root->evaluate($this->schema);

        return $root;
    }
}
