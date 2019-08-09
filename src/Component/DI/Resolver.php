<?php declare(strict_types=1);

/**
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

/**
 * Class Resolver
 *
 * @package Vection\Component\DI
 */
class Resolver
{

    /** @var ArrayObject|Definition[] */
    protected $definitions;

    /**
     * @var ArrayObject
     */
    protected $dependencies;

    /**
     * Resolver constructor.
     *
     * @param ArrayObject $definitions
     * @param ArrayObject $dependencies
     */
    public function __construct(ArrayObject $definitions, ArrayObject $dependencies)
    {
        $this->definitions = $definitions;
        $this->dependencies = $dependencies;
    }

    /**
     * @param string $id
     *
     * @return array
     */
    public function resolveDependencies(string $id): array
    {
        if( ! isset($this->dependencies[$id]) ){
            try {
                if( isset($this->definitions[$id]) ){
                    $this->dependencies[$id] = $this->definitions[$id]->getDependencies();
                }

                if( ! isset($this->dependencies[$id]['construct']) ){
                    $this->dependencies[$id]['construct'] = $this->getConstructorDependencies($id);
                }

                $setter = $this->getInterfaceDependencies($id);

                if( isset($this->dependencies[$id]['setter']) ){
                    $setter = array_merge($this->dependencies[$id]['setter'], $setter);
                }

                # This dependencies will be passed by setter defined by interfaces
                $this->dependencies[$id]['setter'] = $setter;

                # This will used the __annotationInjection method from AnnotationInjection trait
                $this->dependencies[$id]['annotation'] = $this->getAnnotatedDependencies($id);

                # This is for the explicit inject by __inject method
                $this->dependencies[$id]['explicit'] = $this->getExplicitDependencies($id);
            }
            catch( ReflectionException $e ) {
                throw new ContainerException(
                    "Reflection Error while resolving dependencies of class '$id'",
                    $e
                );
            }
        }

        return $this->dependencies[$id];
    }

    /**
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    protected function getConstructorDependencies(string $className): array
    {
        $dependencies = [];
        $constructor = (new ReflectionClass($className))->getConstructor();

        if( $constructor && ($constructParams = $constructor->getParameters()) ) {
            foreach( $constructParams as $param ) {
                if( $param->hasType() && $param->getType() !== null && ! $param->getType()->isBuiltin() ) {
                    $dependencies[] = $param->getClass()->name;
                    $this->resolveDependencies($param->getClass()->name);
                } else {
                    return [];
                }
            }
        }

        if( ! $dependencies && ($parent = get_parent_class($className)) ) {
            do {
                $dependencies = $this->getConstructorDependencies($parent);
            } while( ! $dependencies && ($parent = get_parent_class($parent)) );
        }

        return $dependencies;
    }

    /**
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    protected function getInterfaceDependencies(string $className): array
    {
        $dependencies = [];

        # Add setter injections by interfaces
        if( $interfaces = class_implements($className, true) ) {
            foreach( $interfaces as $interface ) {
                if( isset($this->definitions[$interface]) ) {
                    $dependencies = $this->definitions[$interface]->getDependencies()['setter'] ?? [];
                    foreach( $dependencies as $dependency ){
                        $this->resolveDependencies($dependency);
                    }
                }
            }
        }

        # Add setter injection by parent classes
        if( $parents = class_parents($className, true) ) {
            foreach( $parents as $parent ) {
                if( $parentInterfaceDependencies = $this->getInterfaceDependencies($parent) ) {
                    $dependencies = array_merge($dependencies, $parentInterfaceDependencies);
                }
            }
        }

        return $dependencies;
    }

    /**
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    protected function getAnnotatedDependencies(string $className): array
    {
        $reflection = new ReflectionClass($className);
        $dependencies = [];

        if( $reflection->getTraits()[AnnotationInjection::class] ?? null ) {

            foreach ( $reflection->getProperties() as $property ) {
                if ( ! ($doc = $property->getDocComment()) ) {
                    continue;
                }

                $regex = '/@Inject\("([a-zA-Z\\\\_0-9]+)"\)/';

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
     * @param string $className
     *
     * @return array
     *
     * @throws ReflectionException
     */
    protected function getExplicitDependencies(string $className): array
    {
        $dependencies = [];
        $reflection = new ReflectionClass($className);

        if( $reflection->hasMethod('__inject') ){
            $method = $reflection->getMethod('__inject');

            foreach ( $method->getParameters() ?? [] as $param ) {
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