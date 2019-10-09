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
use Vection\Contracts\Validator\Schema\Json\JsonTypeInterface;

/**
 * Class Definition
 *
 * @package Vection\Component\Validator\Schema\Json
 *
 * @author David Lung <vection@davidlung.de>
 */
abstract class JsonType implements JsonTypeInterface
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
     * JsonType constructor.
     *
     * @param string $name
     */
    public function __construct(? string $name = null)
    {
        $this->name = $name;
    }

    /**
     * @inheritDoc
     */
    public function getName(): ? string
    {
        return $this->name;
    }

    /**
     * @param string $name
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
        $this->required = $schema['@required'] ?? false;
        $this->type = $schema['@type'];
        $this->onEvaluate($schema);
    }

    /**
     * @param array $schema
     */
    abstract protected function onEvaluate(array $schema): void;

    /**
     * @param string      $type
     *
     * @param string|null $name
     *
     * @return JsonType
     */
    protected function createType(string $type, ? string $name = null): JsonType
    {
        $className = __NAMESPACE__.'\\Type\\Json'.ucfirst($type);

        if( ! class_exists($className) ){
            throw new RuntimeException('Cannot instantiate unknown json type: '.$type);
        }

        return new $className($name);
    }
}