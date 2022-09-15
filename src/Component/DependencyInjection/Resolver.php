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

use ArrayObject;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Vection\Component\DependencyInjection\Attributes\Inject;
use Vection\Component\DependencyInjection\Attributes\PreventInjection;
use Vection\Component\DependencyInjection\Exception\DependencyResolverException;
use Vection\Component\DependencyInjection\Traits\AnnotationInjection;
use Vection\Contracts\Cache\CacheInterface;
use Vection\Contracts\DependencyInjection\InstructionInterface;
use Vection\Contracts\DependencyInjection\ResolverInterface;

/**
 * Class Resolver
 *
 * This class resolves the dependencies for a given class name and
 * saves only the information of the dependency classes and how
 * they have to be injected by the Injector class.
 *
 * @package Vection\Component\DependencyInjection
 * @author  David M. Lung <vection@davidlung.de>
 */
class Resolver implements ResolverInterface
{
    /**
     * The cache saves all resolved dependency tree information
     * to avoid resolving on each request. Each new resolved information
     * will be saved directly into the cache storage.
     *
     * @var CacheInterface|null
     */
    protected CacheInterface|null $cache = null;

    /**
     * This array object contains custom dependency definitions
     * which will be considered by the resolving process.
     *
     * @var ArrayObject<string, InstructionInterface>
     */
    protected ArrayObject $instructions;

    /**
     * This property contains all resolved dependency information
     * which will be cached and reused on each injection by the Injector class.
     *
     * @var ArrayObject<string, mixed>
     */
    protected ArrayObject $dependencies;

    /**
     * Resolver constructor.
     *
     * @param ArrayObject<string, InstructionInterface>|null $instructions
     * @param ArrayObject<string, mixed>|null $dependencies
     */
    public function __construct(ArrayObject|null $instructions = null, ArrayObject|null $dependencies = null)
    {
        $this->instructions  = $instructions ?: new ArrayObject();
        $this->dependencies = $dependencies ?: new ArrayObject();
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;

        if ( $cache->contains('dependencies') ) {
            if ( $existing = $this->dependencies->getArrayCopy() ) {
                $cachedDependencies = $cache->getArray('dependencies');
                $dependencies       = array_merge($existing, $cachedDependencies);
                $this->dependencies->exchangeArray($dependencies);
            } else {
                $this->dependencies->exchangeArray($cache->getArray('dependencies'));
            }
        } else if ( $this->dependencies->count() > 0 ) {
            # Apply dependencies to cache which have been resolved before set the cache
            $cache->setArray('dependencies', $this->dependencies->getArrayCopy());
        }
    }

    /**
     * @inheritDoc
     */
    public function addInstruction(InstructionInterface $instruction): void
    {
        $this->instructions[$instruction->getClassName()] = $instruction;
    }

    /**
     * @inheritDoc
     */
    public function getInstruction(string $className): InstructionInterface|null
    {
        return $this->instructions[$className];
    }

    /**
     * @param string $className
     *
     * @return array<string, mixed>
     */
    public function getClassDependencies(string $className): array
    {
        return $this->dependencies[$className] ?: [];
    }

    /**
     * @inheritDoc
     */
    public function resolveDependencies(string $className): array
    {
        if (isset($this->dependencies[$className])) {
            return $this->dependencies[$className];
        }

        $this->dependencies[$className] = [
            'by' => null,
            'constructor' => [],
            'setter' => [],
            'annotation' => [],
            'magic' => [],
            'factory' => null,
        ];

        try {
            $dependencies = &$this->dependencies[$className];

            if (isset($this->instructions[$className])) {
                $instruction  = $this->instructions[$className];
                $dependencies['by'] = $instruction->getBy();
                $dependencies['factory'] = $this->instructions[$className]->getFactory();
            }

            if (!$dependencies['factory']) {
                $dependencies['constructor'] = $this->resolveConstructorDependencies($className);
            }

            # This dependencies will be passed by setter defined by interfaces
            $dependencies['setter'] = $this->resolveInterfaceDependencies($className);

            # This will use the __annotationInjection method from AnnotationInjection trait
            $dependencies['annotation'] = $this->resolveAnnotatedDependencies($className);

            # This is for the magic inject by __inject method
            $dependencies['magic'] = $this->resolveMagicInjectionDependencies($className);

            $this->cache?->setArray('dependencies', $this->dependencies->getArrayCopy());
        }
        catch ( ReflectionException $e ) {
            throw new DependencyResolverException(
                "Reflection Error while resolving dependencies of class '$className'",
                0,
                $e
            );
        }

        return $this->dependencies[$className];
    }

    /**
     * Resolves the dependency information defined by the constructor of the given class.
     *
     * Returns an array contains the class names of the dependencies in the same order
     * as defined by the constructor.
     *
     * @param string $className
     *
     * @return string[] Contains class names of the dependencies.
     *
     * @throws ReflectionException
     */
    protected function resolveConstructorDependencies(string $className): array
    {
        $dependencies = [];
        $constructor  = (new ReflectionClass($className))->getConstructor();

        if ($constructor && ($constructParams = $constructor->getParameters())) {

            if ($constructor->getAttributes(PreventInjection::class)) {
                return $dependencies;
            }

            foreach ($constructParams as $param) {
                $type = $param->getType();

                if ($type === null) {
                    throw new DependencyResolverException(
                        'Constructor parameter injection requires typed, non built-in parameters in '.$className
                    );
                }

                if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
                    if (!$param->isDefaultValueAvailable()) {
                        $this->dependencies[$className]['constructor_has_primitives'] = true;
                    }

                    break;
                }

                $reflection = new ReflectionClass($type->getName());

                if ($reflection->isInterface() && $param->allowsNull()) {
                    if (!isset($this->dependencies[$className]['constructor_nullable_interface'])) {
                        $this->dependencies[$className]['constructor_nullable_interface'] = [];
                    }
                    $this->dependencies[$className]['constructor_nullable_interface'][] = $reflection->getName();
                }

                if ($param->allowsNull() && $param->getAttributes(PreventInjection::class)) {
                    if (!isset($this->dependencies[$className]['constructor_prevent_injection'])) {
                        $this->dependencies[$className]['constructor_prevent_injection'] = [];
                    }
                    $this->dependencies[$className]['constructor_prevent_injection'][] = $reflection->getName();
                }

                $dependencies[] = $reflection->getName();

                if ( $reflection->isInstantiable() ) {
                    $this->resolveDependencies($reflection->getName());
                }
            }
        }

        if ( ! $dependencies && ($parent = get_parent_class($className)) ) {
            do {
                $dependencies = $this->resolveConstructorDependencies($parent);
            } while ( ! $dependencies && ($parent = get_parent_class($parent)) );
        }

        return $dependencies;
    }

    /**
     * Resolves the dependencies information by interface definition.
     *
     * Returns an array contains the setter as key and class name of the
     * dependencies which will be injected as value.
     *
     * @param string $className
     *
     * @return string[] Contains setter methods as key and class as value.
     */
    protected function resolveInterfaceDependencies(string $className): array
    {
        $dependencyClassNames = [];

        # Add setter injections by interfaces
        foreach (class_implements($className) ?: [] as $interface) {
            if (isset($this->instructions[$interface])) {
                foreach ($this->instructions[$interface]->getSetterInjections() as $method => $dependency) {
                    $this->resolveDependencies($dependency);
                    $dependencyClassNames[$method] = $dependency;
                }
            }
        }

        return $dependencyClassNames;
    }

    /**
     * Resolves the annotated dependencies defined by the given class.
     *
     * Returns an array with the internal property name as key and the
     * class name of the dependency as value.
     *
     * @param string $className
     *
     * @return string[]
     *
     * @throws ReflectionException
     */
    protected function resolveAnnotatedDependencies(string $className): array
    {
        $dependencyClassNames = [];
        $reflection           = new ReflectionClass($className);
        $useInjectionTrait    = isset($reflection->getTraits()[AnnotationInjection::class]);

        foreach ( class_parents($className) ?: [] as $parentClassName ) {
            if ( isset((new ReflectionClass($parentClassName))->getTraits()[AnnotationInjection::class]) ) {
                $useInjectionTrait = true;
                foreach($this->resolveAnnotatedDependencies($parentClassName) as $name => $dependencyClassName) {
                    $dependencyClassNames[$name] = $dependencyClassName;
                }
            }
        }

        if ( $useInjectionTrait ) {
            foreach ( $reflection->getProperties() as $property ) {

                $propertyType = null;

                if ($property->getAttributes(Inject::class)) {

                    if (!$property->hasType()) {
                        throw new DependencyResolverException(
                            'The use of attribute based injection requires typed properties. '.
                            "Property {$property->getName()} in class $className has no type."
                        );
                    }

                    $propertyType = $property->getType();

                    if (!$propertyType instanceof ReflectionNamedType || $propertyType->isBuiltin()) {
                        throw new DependencyResolverException(
                            'The use of attribute based injection requires non built-in typed properties. '.
                            "Property {$property->getName()} in class $className has an invalid or built-in type."
                        );
                    }

                    $dependencyClassName = $propertyType->getName();
                    $dependencyClassNames[$property->getName()] = $dependencyClassName;

                    $this->resolveDependencies($dependencyClassName);
                }
            }
        }

        return $dependencyClassNames;
    }

    /**
     * Resolves the magic requested dependencies by the __inject method.
     *
     * Returns an array contains the class names of the dependencies in same
     * order as defined by the given class __inject method.
     *
     * @param string $className
     *
     * @return string[]
     *
     * @throws ReflectionException
     */
    protected function resolveMagicInjectionDependencies(string $className): array
    {
        $dependencyClassNames = [];
        $reflection   = new ReflectionClass($className);

        if ( $reflection->hasMethod('__inject') ) {
            $method = $reflection->getMethod('__inject');

            foreach ($method->getParameters() as $param) {
                if ($param->hasType()) {
                    $type = $param->getType();
                    if ( $type instanceof ReflectionNamedType && ! $type->isBuiltin() ) {
                        $dependencyClassNames[] = $type->getName();
                        continue;
                    }
                }

                throw new DependencyResolverException(
                    "Then magic $className::__inject method expects arguments to be non build-in typed parameters."
                );
            }
        }

        return $dependencyClassNames;
    }
}
