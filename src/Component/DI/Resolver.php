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

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI;

use ArrayObject;
use ReflectionClass;
use ReflectionException;
use Vection\Component\DI\Exception\ContainerException;
use Vection\Component\DI\Traits\AnnotationInjection;
use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class Resolver
 *
 * This class resolves the dependencies for a given class name and
 * saves only the information of the dependency classes and how
 * they have to be injected by the Injector class.
 *
 * @package Vection\Component\DI
 */
class Resolver implements CacheAwareInterface
{

    /**
     * The cache saves all resolved dependency tree information
     * to avoid resolving on each request. Each new resolved information
     * will be saved directly into the cache storage.
     *
     * @var CacheInterface|null
     */
    protected $cache;

    /**
     * This array object contains custom dependency definitions
     * which will be considered by the resolving process.
     *
     * @var ArrayObject|Definition[]
     */
    protected $definitions;

    /**
     * This property contains all resolved dependency information
     * which will be cached and reused on each injection by the Injector class.
     *
     * @var ArrayObject|string[][][]
     */
    protected $dependencies;

    /**
     * Resolver constructor.
     *
     * @param ArrayObject         $definitions
     * @param ArrayObject         $dependencies
     */
    public function __construct(ArrayObject $definitions, ArrayObject $dependencies)
    {
        $this->definitions  = $definitions;
        $this->dependencies = $dependencies;
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
     * Resolves all dependencies of the given class.
     *
     * @param string $className FQCN
     *
     * @return array An array that contains dependency information.
     */
    public function resolveDependencies(string $className): array
    {
        if ( ! isset($this->dependencies[$className]) ) {
            try {
                if ( isset($this->definitions[$className]) ) {
                    $this->dependencies[$className] = $this->definitions[$className]->getDependencies();
                }

                if ( ! isset($this->dependencies[$className]['construct']) ) {
                    $this->dependencies[$className]['construct'] = $this->resolveConstructorDependencies($className);
                }

                $setter = $this->resolveInterfaceDependencies($className);

                if ( isset($this->dependencies[$className]['setter']) ) {
                    $setter = array_merge($this->dependencies[$className]['setter'], $setter);
                }

                # This dependencies will be passed by setter defined by interfaces
                $this->dependencies[$className]['setter'] = $setter;

                # This will used the __annotationInjection method from AnnotationInjection trait
                $this->dependencies[$className]['annotation'] = $this->resolveAnnotatedDependencies($className);

                # This is for the explicit inject by __inject method
                $this->dependencies[$className]['explicit'] = $this->resolveExplicitDependencies($className);

                if ( $this->cache ) {
                    $this->cache->setArray('dependencies', $this->dependencies->getArrayCopy());
                }
            } catch ( ReflectionException $e ) {
                throw new ContainerException(
                    "Reflection Error while resolving dependencies of class '$className'",
                    0,
                    $e
                );
            }
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
     * @return array Contains class names of the dependencies.
     *
     * @throws ReflectionException
     */
    public function resolveConstructorDependencies(string $className): array
    {
        $dependencies = [];
        $constructor  = (new ReflectionClass($className))->getConstructor();

        if ( $constructor && ($constructParams = $constructor->getParameters()) ) {
            foreach ( $constructParams as $param ) {
                if ( $param->hasType() && $param->getType() !== null && ! $param->getType()->isBuiltin() ) {
                    $dependencies[] = $param->getClass()->name;
                    if ( $param->getClass()->isInstantiable() ) {
                        $this->resolveDependencies($param->getClass()->name);
                    }
                } else {
                    return [];
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
     * @return array Contains setter methods as key and class as value.
     *
     * @throws ReflectionException
     */
    public function resolveInterfaceDependencies(string $className): array
    {
        $dependencies = [];

        # Add setter injections by interfaces
        if ( $interfaces = class_implements($className, true) ) {
            foreach ( $interfaces as $interface ) {
                if ( isset($this->definitions[$interface]) ) {
                    $deps = ($this->definitions[$interface]->getDependencies()['setter'] ?? []);
                    foreach ( $deps as $method => $dependency ) {
                        $this->resolveDependencies($dependency);
                        $dependencies[$method] = $dependency;
                    }
                }
            }
        }

        # Add setter injection by parent classes
        if ( $parents = class_parents($className, true) ) {
            foreach ( $parents as $parent ) {
                if ( $parentInterfaceDependencies = $this->resolveInterfaceDependencies($parent) ) {
                    foreach ($parentInterfaceDependencies as $method => $parentInterfaceDependency) {
                        $dependencies[$method] = $parentInterfaceDependency;
                    }
                }
            }
        }

        return $dependencies;
    }

    /**
     * Resolves the annotated dependencies defined by the given class.
     *
     * Returns an array with the internal property name as key and the
     * class name of the dependency as value.
     *
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    public function resolveAnnotatedDependencies(string $className): array
    {
        $reflection        = new ReflectionClass($className);
        $dependencies      = [];
        $useInjectionTrait = ($reflection->getTraits()[AnnotationInjection::class] ?? false);

        # Add annotation injection by parent classes
        if ( $parents = class_parents($className, true) ) {
            foreach ( $parents as $parent ) {
                if ( isset((new ReflectionClass($parent))->getTraits()[AnnotationInjection::class]) ) {
                    $useInjectionTrait = true;
                }
                if ( $parentAnnotationDependencies = $this->resolveAnnotatedDependencies($parent) ) {
                    $dependencies = $parentAnnotationDependencies;
                }
            }
        }

        if ( $useInjectionTrait || $dependencies ) {

            foreach ( $reflection->getProperties() as $property ) {
                if ( ! ($doc = $property->getDocComment()) ) {
                    continue;
                }

                $regex = '/@Inject\(["\']+([a-zA-Z\\\\_0-9]+)["\']+\)/';

                if ( preg_match_all($regex, $doc, $match, PREG_SET_ORDER) ) {
                    foreach ( $match as $m ) {
                        $dependencies[$property->getName()] = $m[1];
                        $this->resolveDependencies($m[1]);
                    }
                }
            }
        }

        return $dependencies;
    }

    /**
     * Resolves the explicit requested dependencies by the __inject method.
     *
     * Returns an array contains the class names of the dependencies in same
     * order as defined by the given class __inject method.
     *
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    public function resolveExplicitDependencies(string $className): array
    {
        $dependencies = [];
        $reflection   = new ReflectionClass($className);

        if ( $reflection->hasMethod('__inject') ) {
            $method = $reflection->getMethod('__inject');

            foreach ( ($method->getParameters() ?? []) as $param ) {
                if ( $param->hasType() && $param->getType() !== null && ! $param->getType()->isBuiltin() ) {
                    $dependencies[] = $param->getClass()->name;
                } else {
                    # We can only inject if ALL parameters are injectable objects
                    return [];
                }
            }
        }

        return $dependencies;
    }
}
