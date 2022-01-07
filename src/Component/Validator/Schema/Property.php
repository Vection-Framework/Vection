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

declare(strict_types=1);

namespace Vection\Component\Validator\Schema;

use RuntimeException;
use Vection\Component\Validator\Schema\Exception\InvalidPropertyException;
use Vection\Component\Validator\Schema\Exception\SchemaException;
use Vection\Component\Validator\ValidatorFactory;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;
use Vection\Contracts\Validator\Schema\PropertyInterface;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;
use Vection\Contracts\Validator\ValidatorInterface;

/**
 * Class JsonProperty
 *
 * @package Vection\Component\Validator\Schema
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Property implements PropertyInterface
{
    protected string    $type;
    protected string    $name;
    protected ?bool     $required = null;
    protected ?bool     $nullable = null;
    /** @var ValidatorInterface[] */
    protected array     $validators;
    protected array     $templates;
    protected ?string   $template = null;
    protected ?Property $parent;
    protected int       $maxTemplateRecursion;

    /**
     * @param string        $name
     * @param Property|null $parent
     * @param array         $templates
     * @param int           $maxTemplateRecursion
     */
    public function __construct(
        string $name, ? Property $parent = null, array $templates = [], int $maxTemplateRecursion = 3
    )
    {
        $this->name                 = $name;
        $this->parent               = $parent;
        $this->templates            = $templates;
        $this->maxTemplateRecursion = $maxTemplateRecursion;
        $this->validators           = [];
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
    public function isNullable(): bool
    {
        return $this->nullable;
    }

    /**
     * @inheritDoc
     */
    public function evaluate(array $schema): void
    {
        if ( isset($schema['@property']['@template']) ) {
            $templateName = $schema['@property']['@template'];
            $templateSchema = $this->getTemplate($templateName);
            $schema['@property'] = $templateSchema + ['@fromTemplate' => $templateName];
        }

        if ( isset($schema['@properties']) ) {
            foreach ( $schema['@properties'] as $name => $propertySchema ) {
                if ( isset($propertySchema['@template']) ) {
                    $templateName = $propertySchema['@template'];
                    $templateSchema = $this->getTemplate($templateName);
                    $schema['@properties'][$name] = $templateSchema + ['@fromTemplate' => $templateName];
                }
            }
        }

        if ( isset($schema['@fromTemplate']) ) {
            $this->template = $schema['@fromTemplate'];

            $templates = [];

            $templateChain = $this->resolveTemplateChain();
            $templateChainString = implode('/', $templateChain);

            foreach ( $templateChain as $template ) {
                $templates[] = $template;
                if ( substr_count($templateChainString, implode('/', $templates)) === $this->maxTemplateRecursion ) {
                    return;
                }
            }
        }

        if ( isset($schema['@validator']) ) {

            if ( is_string($schema['@validator']) ) {
                $schema['@validator'] = [
                    '@name' => $schema['@validator']
                ];
            }

            $schema['@validators'] = [$schema['@validator']];
            unset($schema['@validator']);
        }

        if ( isset($schema['@validators']) ) {

            foreach ( $schema['@validators'] as $validator ) {
                $this->validators[] = (new ValidatorFactory())->create(
                    $validator['@name'],
                    array_values(($validator['@constraints'] ?? []))
                );
            }

            unset($schema['@validators']);
        }

        $this->required = ($schema['@required'] ?? false);
        $this->nullable = ($schema['@nullable'] ?? false);
        $this->type     = $schema['@type'];
        $this->onEvaluate($schema);
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        if ($this->nullable && $value === null) {
            return;
        }

        if ( count($this->validators) > 0 ) {
            foreach ( $this->validators as $validator ) {
                $violation = $validator->validate($value);

                if ( $violation !== null ) {
                    $message = 'Property %s is invalid: '. $violation->getMessage();
                    throw new InvalidPropertyException($this->name, $message);
                }
            }
        }

        $this->onValidate($value);
    }

    /**
     * @param array $schema
     *
     * @throws SchemaExceptionInterface
     */
    abstract protected function onEvaluate(array $schema): void;

    /**
     * @param string|int|float|array|bool $value
     *
     * @throws PropertyExceptionInterface
     */
    abstract protected function onValidate($value): void;

    /**
     * @param string $name
     * @param string $type
     *
     * @return Property
     */
    protected function createProperty(string $name, string $type): PropertyInterface
    {
        $className = __NAMESPACE__.'\\Property\\'.ucfirst($type).'Property';

        if ( ! class_exists($className) ) {
            throw new RuntimeException('Cannot instantiate unknown property type: '.$type);
        }

        return new $className($name, $this, $this->templates, $this->maxTemplateRecursion);
    }

    /**
     * @param string $name
     *
     * @return array
     *
     * @throws SchemaExceptionInterface
     */
    protected function getTemplate(string $name): array
    {
        if ( ! isset($this->templates[$name]) ) {
            throw new SchemaException("Cannot use property template ($name) because it does not exists.");
        }

        return $this->templates[$name];
    }

    /**
     * @return array
     */
    public function resolveTemplateChain(): array
    {
        $chain = $this->parent ? $this->parent->resolveTemplateChain() : [];

        if ($this->template) {
            $chain[] = $this->template;
        }

        return $chain;
    }
}
