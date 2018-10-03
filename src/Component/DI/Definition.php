<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI;

/**
 * Class Definition
 *
 * @package Vection\Component\DI
 */
class Definition
{
    /**
     * This property contains all the definitions for
     * the given class.
     *
     * @var string
     */
    protected $definition;

    /**
     * Definition constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->definition = [
            'className'       => ltrim($className, '\\'),
            'constructParams' => [],
            'shared'          => true,
            'instanceClosure' => null,
            'dependencies'    => [],
        ];
    }

    /**
     * Returns the class name as target for this definition.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->definition['className'];
    }

    /**
     * @param array $params
     *
     * @return Definition
     */
    public function construct(...$params): Definition
    {
        $this->definition['constructParams'] = $params;

        return $this;
    }

    /**
     * @param callable $closure
     *
     * @return Definition
     */
    public function instance(callable $closure): Definition
    {
        $this->definition['instanceClosure'] = $closure;

        return $this;
    }

    /**
     * @param string $className
     * @param string $propertyName
     *
     * @return Definition
     */
    public function inject(string $className, string $propertyName = ''): Definition
    {
        $this->definition['dependencies'][$propertyName ?: $className] = $className;

        return $this;
    }

    /**
     * @param bool $shared
     *
     * @return Definition
     */
    public function shared(bool $shared): Definition
    {
        $this->definition['shared'] = $shared;

        return $this;
    }

    /**
     * Returns the definition as array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->definition;
    }
}
