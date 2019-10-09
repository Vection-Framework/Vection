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
use Vection\Component\Validator\Schema\Json\Type\JsonObject;
use Vection\Contracts\Validator\Schema\Json\JsonTypeInterface;
use Vection\Contracts\Validator\Schema\Json\SchemaInterface;

/**
 * Class Schema
 *
 * @package Vection\Component\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
class Schema implements SchemaInterface
{
    /**
     * @var array
     */
    protected $schema;

    /**
     * Schema constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        if( ! file_exists($path) ){
            throw new RuntimeException("Json Schema: Cannot load schema from file {$path}, file not exits.");
        }

        $schema = file_get_contents($path);

        if( $schema === false ){
            throw new RuntimeException("Unable to load schema from file {$path}");
        }

        $this->schema = json_decode($schema, true);

        if( json_last_error() !== JSON_ERROR_NONE ){
            throw new RuntimeException('Invalid json schema file: '.json_last_error_msg());
        }
    }

    /**
     * @inheritDoc
     */
    public function evaluate(): JsonTypeInterface
    {
        $root = new JsonObject('$');
        $root->evaluate($this->schema);

        return $root;
    }
}