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
 *
 * @author David Lung <vection@davidlung.de>
 */
abstract class Property implements PropertyInterface
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
     * @var ValidatorInterface[]
     */
    protected $validators;

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
        $this->validators = [];
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

        if( isset($schema['@validator']) ){

            if( is_string($schema['@validator']) ){
                $schema['@validator'] = [
                    '@name' => $schema['@validator']
                ];
            }

            $schema['@validators'] = [$schema['@validator']];
            unset($schema['@validator']);
        }

        if( isset($schema['@validators']) ){

            foreach( $schema['@validators'] as $validator ){
                $this->validators[] = (new ValidatorFactory())->create(
                    $validator['@name'],
                    array_values($validator['@constraints'] ?? [])
                );
            }

            unset($schema['@validators']);
        }

        $this->required = $schema['@required'] ?? false;
        $this->type = $schema['@type'];
        $this->onEvaluate($schema);
    }

    /**
     * @inheritDoc
     */
    public function validate($value): void
    {
        if( count($this->validators) > 0 ){
            foreach( $this->validators as $validator ){
                $violation = $validator->validate($value);

                if( $violation !== null ){
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
     * @param string|int|float|array $value
     *
     * @throws PropertyExceptionInterface
     */
    abstract protected function onValidate($value): void;

    /**
     * @param string      $type
     *
     * @param string|null $name
     *
     * @return Property
     */
    protected function createProperty(string $type, ? string $name = null): PropertyInterface
    {
        $className = __NAMESPACE__.'\\Property\\'.ucfirst($type).'Property';

        if( ! class_exists($className) ){
            throw new RuntimeException('Cannot instantiate unknown property type: '.$type);
        }

        return new $className($name, $this->templates);
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
        if( ! isset($this->templates[$name]) ){
            throw new SchemaException("Cannot use property template ({$name}) because it does not exists.");
        }

        return $this->templates[$name];
    }
}