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

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Vection\Component\Cache\Traits\CacheAwareTrait;
use Vection\Component\DI\Exception\ContainerException;
use Vection\Component\DI\Exception\InvalidArgumentException;
use Vection\Component\DI\Exception\NotFoundException;
use Vection\Component\DI\Exception\RuntimeException;
use Vection\Contracts\Cache\CacheAwareInterface;

/**
 * Class Container
 *
 * @package Vection\Component\DI
 */
class Container implements ContainerInterface, LoggerAwareInterface, CacheAwareInterface
{
    use LoggerAwareTrait, CacheAwareTrait;

    /** @var array */
    protected $scopes;

    /** @var Inventory */
    protected $inventory;

    /** @var object[] */
    protected $shares;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->inventory = new Inventory();
        $this->shares[self::class] = $this;
    }

    /**
     * Loads a definition file, which contains the
     * definitions for the classes that will be handled by the
     * container. This method uses glob to support paths with wildcards
     * and dynamic path types that are supported by glob too. This gives the possibility
     * to add multiple files.
     *
     * @param string $path
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function load(string $path): void
    {
        if( ! ($pathArray = \glob($path)) ) {
            throw new InvalidArgumentException("Given path is not valid or doesn't exists.");
        }

        foreach( $pathArray as $_path ) {
            /** @noinspection PhpIncludeInspection */
            $definition = require $_path;

            if( ! \is_array($definition) ) {
                throw new RuntimeException("Cannot load definition from {$_path}.");
            }
            $this->inventory->addDefinitionArray($definition);
        }
    }

    /**
     * Sets a new set of namespace scopes which can be
     * used to load all classes in the scopes without
     * to define them in the container configuration.
     *
     * @param array $scopes
     */
    public function setNamespaceScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        # REMOVE LEADING BACKSLASH
        $className = ltrim($id, "\\");

        # RETURN IF OBJECT IS A SHARED ONE AND ALREADY EXIST
        if( isset($this->shares[$className]) ) {
            return $this->shares[$className];
        }

        # CHECK IF REQUESTED OBJECT HAS AN ENTRY
        if( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        # OBJECT CREATION + INJECT BY CONFIG / CONSTRUCTOR PARAMETERS
        try {
            $object = $this->createObject($className);
        } catch( \ReflectionException $e ) {
            $this->logger->error($e->getMessage());
            throw new ContainerException(
                sprintf('%s: Error while creating object for id "%s": %s %s.',
                    self::class, $className, $e->getMessage(), $e->getTraceAsString()
                ), 0, $e
            );
        }

        # INJECTIONS BY SEVERAL WAYS
        $this->injectIntoObject($object);

        # OBJECT HANDLING (SINGLETON/FACTORY)
        if( $this->inventory->isShared($className) ) {
            $this->shares[$className] = $object;
        }

        # CALL INITIAL METHOD IF EXISTS
        if( method_exists($object, '__init') ) {
            try {
                $method = new \ReflectionMethod(\get_class($object), '__init');
                $method->isPublic() AND $object->__init();
            } catch( \ReflectionException $e ) {
                if( \strpos($e->getMessage(), '__init') === false ){
                    throw new $e;
                }
            }
        }

        return $object;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    private function evaluate($id): bool
    {
        # Check if there is an defined entry for the given id
        if( ! $this->has($id) ) {
            # There is no entry for this id

            if( ! $this->scopes ) {
                # There is no entry and no scope, so return an empty array
                return false;
            }

            # Check if the id is part of a registered namespace scope
            foreach( $this->scopes as $scope ) {
                if( \strpos($id, $scope) === 0 ) {
                    # The id matches a scope, so we allow to register a default definition for this id
                    $this->set($id);
                }
            }

            if( ! $this->has($id) ) {
                # There is no entry for this id and it is not a part of a scope
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id): bool
    {
        return $this->inventory->has(\trim($id, "\\"));
    }

    /**
     * Register a new entry by given identifier. The second parameter can be used
     * for entry settings
     *
     * @param string     $className
     * @param Definition $definition
     *
     * @return Container
     */
    public function set(string $className, ? Definition $definition = null): Container
    {
        $this->inventory->set(\trim($className, "\\"), $definition ?: new Definition($className));

        return $this;
    }

    /**
     * @param string $className
     *
     * @return object
     *
     * @throws \ReflectionException
     */
    private function createObject(string $className): object
    {
        if( $params = $this->inventory->getConstructParams($className) ) {
            return new $className(...$params);
        }

        if( $closure = $this->inventory->getInstanceClosure($className) ) {
            return $closure($this);
        }

        if( $params = $this->getConstructorDependencies($className) ) {
            return new $className(...$params);
        }

        return new $className();
    }

    ///////////////////////////////////////////////////////////////////////
    // OBJECT HANDLING (CREATION + INJECTION)
    ///////////////////////////////////////////////////////////////////////

    /**
     * @param string $className
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    private function getConstructorDependencies(string $className): array
    {
        $params = [];
        $constructor = (new \ReflectionClass($className))->getConstructor();

        if( $constructor && ($constructParams = $constructor->getParameters()) ) {
            foreach( $constructParams as $param ) {
                if( $param->hasType() && $param->getType() !== null && ! $param->getType()->isBuiltin() ) {
                    $params[] = $this->get($param->getClass()->name);
                } else {
                    return [];
                }
            }
        }

        if( ! $params && ($parent = get_parent_class($className)) ) {
            do {
                $params = $this->getConstructorDependencies($parent);
            } while( ! $params && ($parent = get_parent_class($parent)) );
        }

        return $params;
    }

    /**
     * @param object $object
     */
    private function injectIntoObject(object $object): void
    {
        # INJECTION BY (Object) CONFIG (SETTER / __SET)
        $this->injectBySetter($object);

        # INJECTION BY TRAIT (__dependencyInjection)
        $this->injectByTraits($object);

        # INJECTION BY METHOD (__inject)
        $this->injectByMethod($object);
    }

    /**
     * @param object $object
     */
    private function injectBySetter(object $object): void
    {
        $className = \trim(\get_class($object), "\\");

        # Add setter injections by interfaces
        if( $interfaces = class_implements($className, true) ) {
            foreach( $interfaces as $interface ) {
                if( $this->has($interface) && ($dependencies = $this->inventory->getDependencies($interface)) ) {
                    $this->inventory->addDependencies($className, $dependencies);
                }
            }
        }

        # Add setter injection by parent classes
        if( $parents = class_parents($className, true) ) {
            foreach( $parents as $parent ) {
                if( $this->has($parent) && ($dependencies = $this->inventory->getDependencies($parent)) ) {
                    $this->inventory->addDependencies($className, $dependencies);
                }
            }
        }

        foreach( $this->inventory->getDependencies($className) as $property => $dependency ) {

            $property = \ltrim($property, '\\');

            if( \strpos($property, '\\') !== false ) {
                $property = \substr($property, \strrpos($property, '\\') + 1);
            }

            $setter = 'set'.\ucfirst($property);

            if( \method_exists($object, $setter) ) {
                $object->$setter($this->get($dependency));
            }
        }

    }

    /**
     * @param object $object
     */
    private function injectByTraits(object $object): void
    {
        # ContainerAware trait
        if( \method_exists($object, '__setContainer') ) {
            $object->__setContainer($this);
        }

        # AnnotationInjection trait
        if( \method_exists($object, '__annotationInjection') ) {

            $dependencies = [];

            if( $this->cache ){
                $pool = $this->cache->getPool('Vection.DI');
                $key = 'annotatedDependencies.'.\get_class($object);
                if( $pool->contains($key) ){
                    $dependencies = $pool->getArray($key);
                }
            }

            if( ! $dependencies ){
                foreach ( ( new \ReflectionObject($object) )->getProperties() as $property ) {
                    if ( ! ($doc = $property->getDocComment()) ) {
                        continue;
                    }

                    $regex = '/@Inject\("([a-zA-Z\\\\_0-9]+)"\)/';

                    if ( \preg_match_all($regex, $doc, $match, PREG_SET_ORDER) ) {
                        foreach ( $match as $m ) {
                            $dependencies[$property->getName()] = $m[1];
                        }
                    }
                }

                if( $this->cache && $dependencies ){
                    $pool = $this->cache->getPool('Vection.DI');
                    $key = 'annotatedDependencies.'.\get_class($object);
                    $pool->setArray($key, $dependencies);
                }
            }

            foreach( $dependencies as $property => $dependencyId ) {
                $dependencies[$property] = $this->get($dependencyId);
            }

            $object->__annotationInjection($dependencies);
        }
    }

    /**
     * @param object $object
     */
    private function injectByMethod(object $object): void
    {
        if( \method_exists($object, '__inject') ) {
            $dependencies = [];

            if( $this->cache ){
                $pool = $this->cache->getPool('Vection.DI');
                $key = 'injectionDependencies.'.\get_class($object);
                if( $pool->contains($key) ){
                    $dependencies = $pool->getArray($key);
                }
            }

            if( ! $dependencies ){
                try {
                    $method = new \ReflectionMethod($object, '__inject');

                    foreach ( $method->getParameters() ?? [] as $param ) {
                        if ( $param->hasType() && $param->getType() !== null && ! $param->getType()->isBuiltin() ) {
                            $dependencies[] = $this->get($param->getClass()->name);
                        } else {
                            return;
                        }
                    }

                    if( $this->cache && $dependencies ){
                        $pool = $this->cache->getPool('Vection.DI');
                        $key = 'injectionDependencies.'.\get_class($object);
                        $pool->setArray($key, $dependencies);
                    }
                }
                catch ( \ReflectionException $e ) {
                    # Never run in this line, covered by method_exists condition
                }
            }

            if ( $dependencies ) {
                \call_user_func_array([ $object, '__inject' ], $dependencies);
            }
        }
    }

    /**
     * Creates a new object by its identifier. The constructor parameters
     * will be resolved and pass by the object container automatically if the
     * second parameter is not set, otherwise the new object will be created
     * with given parameters.
     *
     * @param string $identifier      The identifier of registered entry.
     * @param array  $constructParams Parameter that should be passed to constructor.
     * @param bool   $shared          Whether the new object should be shared or not.
     *
     * @return mixed The new created object.
     *
     * @throws NotFoundException
     * @throws ContainerException
     */
    public function create(string $identifier, array $constructParams = [], bool $shared = true): object
    {
        # REMOVE LEADING BACKSLASH
        $className = ltrim($identifier, "\\");

        if( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        # CREATE OBJECT CONFIG
        $this->inventory->setShared($className, $shared);
        $this->inventory->setConstructParams($className, $constructParams);

        # OBJECT CREATION + INJECT BY CONFIG / CONSTRUCTOR PARAMETERS
        try {
            $object = $this->createObject($className);
        } catch( \ReflectionException $e ) {
            $this->logger->error($e->getMessage());
            throw new ContainerException(
                sprintf(
                    '%s: Error while creating object for id "%s": %s %s.',
                    self::class, $className, $e->getMessage(), $e->getTraceAsString()
                )
            );
        }

        # INJECTIONS BY SEVERAL WAYS
        $this->injectIntoObject($object);

        # OBJECT HANDLING (SINGLETON/FACTORY)
        if( $shared ) {
            $this->shares[$className] = $object;
        }

        # CALL INITIAL METHOD IF EXISTS
        if( method_exists($object, '__init') ) {
            try {
                $method = new \ReflectionMethod(\get_class($object), '__init');
                $method->isPublic() AND $object->__init();
            } catch( \ReflectionException $e ) {
                if( \strpos($e->getMessage(), '__init') === false ){
                    throw new $e;
                }
            }
        }

        return $object;
    }

    /**
     * Adds and register a new shared object to the container. The identifier
     * will be the FQCN of the given object.
     *
     * @param object     $object
     * @param Definition $definition
     *
     * @return mixed Returns the given object.
     */
    public function add(object $object, ? Definition $definition = null): object
    {
        $className = \trim(\get_class($object), "\\");
        $this->set($className, $definition);
        $this->shares[$className] = $object;
        $this->injectIntoObject($object);

        return $object;
    }
}

/**
 * Returns an new instance of DefinitionInterface.
 *
 * @param string $className
 *
 * @return Definition
 */
function def(string $className): Definition
{
    return new Definition($className);
}