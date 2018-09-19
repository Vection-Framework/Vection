<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI;

/**
 * Class Inventory
 *
 * @package Vection\Component\DI
 */
class Inventory
{
    /**
     * @var array
     */
    protected $entries = [];

    /**
     * @param Definition[] $definitions
     */
    public function addDefinitionArray(array $definitions): void
    {
        foreach ( $definitions as $key => $definition ) {
            $this->entries[$definition->getClassName()] = $definition->toArray();
        }
    }

    /**
     * @param string $className
     * @param Definition $definition
     */
    public function set(string $className, Definition $definition): void
    {
        $this->entries[$className] = $definition->toArray();
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function has(string $className): bool
    {
        return \array_key_exists($className, $this->entries);
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function get(string $className): array
    {
        return $this->entries[$className] ?? [];
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function getConstructParams(string $className): array
    {
        return $this->entries[$className]['constructParams'] ?? [];
    }

    /**
     * @param string $className
     *
     * @return callable|null
     */
    public function getInstanceClosure(string $className): ?callable
    {
        return $this->entries[$className]['instanceClosure'] ?? null;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function getDependencies(string $className): array
    {
        return $this->entries[$className]['dependencies'] ?? [];
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    public function isShared(string $className): bool
    {
        return $this->entries[$className]['shared'];
    }

    /**
     * @param string $className
     * @param bool $shared
     */
    public function setShared(string $className, bool $shared): void
    {
        $this->entries[$className]['shared'] = $shared;
    }

    /**
     * @param string $className
     * @param array $constructParams
     */
    public function setConstructParams(string $className, array $constructParams): void
    {
        $this->entries[$className]['constructParams'] = $constructParams;
    }

    /**
     * @param string $className
     * @param array $dependencies
     */
    public function addDependencies(string $className, array $dependencies): void
    {
        $this->entries[$className]['dependencies'] = array_merge(
            $this->entries[$className]['dependencies'], $dependencies
        );
    }
}