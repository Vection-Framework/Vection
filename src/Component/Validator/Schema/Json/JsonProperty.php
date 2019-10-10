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
use Vection\Component\Validator\Schema\Json\Exception\JsonSchemaException;
use Vection\Contracts\Validator\Schema\Json\JsonPropertyInterface;
use Vection\Contracts\Validator\Schema\Json\JsonSchemaExceptionInterface;

/**
 * Class JsonProperty
 *
 * @package Vection\Component\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
abstract class JsonProperty implements JsonPropertyInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var bool
     */
    protected $required;

    /**
     * @var array
     */
    private $templates;

    /**
     * JsonProperty constructor.
     *
     * @param string $name
     * @param array  $templates
     */
    public function __construct(? string $name = null, array $templates = [])
    {
        $this->name = $name;
        $this->templates = $templates;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ? string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(array $schema): void
    {
        if( isset($schema['@template']) ){
            $schema = $this->getTemplate($schema['@template']);
            unset($schema['@template']);
        }

        $this->required = $schema['@required'] ?? false;
        $this->type = $schema['@type'];
        $this->onEvaluate($schema);
    }

    /**
     * @param array $schema
     *
     * @throws JsonSchemaExceptionInterface
     */
    abstract protected function onEvaluate(array $schema): void;

    /**
     * @param string      $type
     *
     * @param string|null $name
     *
     * @return JsonProperty
     */
    protected function createType(string $type, ? string $name = null): JsonProperty
    {
        $className = __NAMESPACE__.'\\Property\\Json'.ucfirst($type);

        if( ! class_exists($className) ){
            throw new RuntimeException('Cannot instantiate unknown json property type: '.$type);
        }

        return new $className($name, $this->templates);
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @throws JsonSchemaExceptionInterface
     */
    protected function getTemplate(string $name): array
    {
        if( ! isset($this->templates[$name]) ){
            throw new JsonSchemaException("Cannot use property template ({$name}) because it does not exists.");
        }

        return $this->templates[$name];
    }
}