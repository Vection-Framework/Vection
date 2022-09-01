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

namespace Vection\Component\DependencyInjection;

/**
 * Class Definition
 *
 * @package Vection\Component\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Definition
{

    /** @var string */
    protected string $id;

    /** @var callable */
    protected $factory;

    /** @var boolean */
    protected bool $shared = true;

    /** @var string[] */
    protected array $dependencies = [];

    /**
     * Definition constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        $this->id = ltrim($className, '\\');
    }

    /**
     * @param callable $closure
     *
     * @return Definition
     */
    public function factory(callable $closure): Definition
    {
        $this->factory = $closure;
        return $this;
    }

    /**
     * @param array<int, mixed> $params
     *
     * @return Definition
     */
    public function construct(...$params): Definition
    {
        $this->dependencies['construct'] = $params;
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
        $setter = $propertyName ?: $className;

        if (str_contains($setter, "\\")) {
            $setter = substr(strrchr($setter, "\\"), 1);
        }

        $setter = 'set' . ucfirst($setter);

        if ( ! isset($this->dependencies['setter']) ) {
            $this->dependencies['setter'] = [];
        }

        $this->dependencies['setter'][$setter] = $className;

        return $this;
    }

    /**
     * @param bool $shared
     *
     * @return Definition
     */
    public function shared(bool $shared): Definition
    {
        $this->shared = $shared;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @return callable|null
     */
    public function getFactory(): ? callable
    {
        return $this->factory;
    }

    /**
     * @return bool
     */
    public function isShared(): bool
    {
        return $this->shared;
    }
}
