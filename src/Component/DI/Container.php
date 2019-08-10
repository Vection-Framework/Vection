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
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use ReflectionException;
use ReflectionMethod;
use Vection\Component\DI\Exception\InvalidArgumentException;
use Vection\Component\DI\Exception\NotFoundException;
use Vection\Component\DI\Exception\RuntimeException;
use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class Container
 *
 * This class provides dependency injection by constructor, interface, annotation
 * and explicit definition. An optional configuration file can be used to register
 * and define dependencies.
 *
 * @package Vection\Component\DI
 */
class Container implements ContainerInterface, LoggerAwareInterface, CacheAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Contains namespaces which will be used for auto setting classes
     * into the registry. All classes in this namespaces no longer have
     * to be registered by the Container::set or config file.
     * 
     * @var array
     */
    protected $registeredNamespaces;

    /**
     * This class resolves all dependencies of a given class and saves
     * the information about the dependency and how they have to be injected.
     *
     * @var Resolver
     */
    protected $resolver;

    /**
     * The Injector is responsible for the injection of dependencies
     * into the given object. It uses the dependency information resolved
     * by the Resolver class.
     *
     * @var Injector
     */
    protected $injector;

    /**
     * Contains all shared objects which will be only instantiate
     * once an injected into all other objects.
     *
     * @var object[]
     */
    protected $sharedObjects;

    /**
     * This array object contains custom dependency definitions
     * which will be considered by the resolving process.
     *
     * @var Definition[]|ArrayObject
     */
    protected $definitions;

    /**
     * This property contains all resolved dependency information
     * which will be cached and reused on each injection by the Injector class.
     *
     * @var string[][][]|ArrayObject
     */
    protected $dependencies;

    /**
     * Container constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->sharedObjects[self::class] = $this;
        $this->definitions = new ArrayObject();
        $this->dependencies = new ArrayObject();
        $this->resolver = new Resolver($this->definitions, $this->dependencies);
        $this->injector = new Injector($this, $this->dependencies);
    }

    /**
     * @inheritDoc
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->resolver->setCache($cache->getPool('DI'));
    }

    /**
     * All objects of the given namespaces will be auto registered at runtime
     * for injection into other objects without the need to define them
     * in the config or by set/add methods. Pass ['*'] as wildcard to register all namespaces.
     *
     * @param array $scopes
     */
    public function registerNamespace(array $scopes): void
    {
        $this->registeredNamespaces = $scopes;
    }

    /**
     * Loads a definition file, which contains the
     * definitions for the classes that will be handled by the
     * container. This method uses glob to support paths with wildcards
     * and dynamic path types that are supported by glob too. This gives the possibility
     * to add multiple files.
     *
     * @param string $path
     */
    public function load(string $path): void
    {
        if( ! ($pathArray = glob($path)) ) {
            throw new InvalidArgumentException("Given path is not valid or doesn't exists.");
        }

        foreach( $pathArray as $_path ) {
            /** @noinspection PhpIncludeInspection */
            $definitions = require $_path;

            if( ! is_array($definitions) ) {
                throw new RuntimeException("Cannot load definition from {$_path}.");
            }

            foreach( $definitions as $definition ){
                if( ! $definition instanceof Definition ){
                    throw new RuntimeException(
                        'Invalid configuration file: Each entry must be of type Definition.'
                    );
                }

                $this->definitions[$definition->getId()] = $definition;
            }
        }
    }

    /**
     * @param string $className
     *
     * @param array  $constructParams
     *
     * @return object
     */
    private function createObject(string $className, array $constructParams = []): object
    {
        if( $constructParams ){
            return new $className(...$constructParams);
        }

        if( $this->definitions->offsetExists($className) ){

            if( $factory = $this->definitions[$className]->getFactory() ){
                return $factory($this);
            }

            $dependencies = $params = $this->definitions[$className]->getDependencies();

            if( $params = ($dependencies['construct'] ?? []) ){
                return new $className(...$params);
            }
        }

        return new $className();
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

            if( ! $this->registeredNamespaces ) {
                # There is no entry and no scope, so return an empty array
                return false;
            }

            if( $this->registeredNamespaces[0] === '*' ){
                return true;
            }

            # Check if the id is part of a registered namespace scope
            foreach( $this->registeredNamespaces as $namespace ) {
                if( strpos($id, $namespace) === 0 ) {
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
     * @param object $object
     */
    private function initializeObject(object $object): void
    {
        if( method_exists($object, '__init') ) {
            try {
                $method = new ReflectionMethod(get_class($object), '__init');
                $method->isPublic() AND $object->__init();
            }
            catch( ReflectionException $e ) {
                if( strpos($e->getMessage(), '__init') === false ){
                    throw new $e;
                }
            }
        }
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
        $className = trim($id, "\\");
        return isset($this->definitions[$className]) || isset($this->dependencies[$className]);
    }

    /**
     * Register a new entry by given identifier. The second parameter can be used
     * for entry definition
     *
     * @param string     $className
     * @param Definition $definition
     *
     * @return Container
     */
    public function set(string $className, ? Definition $definition = null): Container
    {
        $className = trim($className, "\\");
        $this->definitions[$className] = $definition ?: new Definition($className);
        $this->resolver->resolveDependencies($className);

        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     * If the requested entry has dependencies, these will be injected before
     * return this object.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return object
     */
    public function get($id)
    {
        $className = ltrim($id, "\\");

        if( isset($this->sharedObjects[$className]) ) {
            return $this->sharedObjects[$className];
        }

        if( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        $this->resolver->resolveDependencies($className);
        $object = $this->createObject($className);
        $this->injector->injectDependencies($object);

        if( ($definition = $this->definitions[$className] ?? null) && $definition->isShared() ) {
            $this->sharedObjects[$className] = $object;
        }

        $this->initializeObject($object);

        return $object;
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
     * @return object The new created object.
     */
    public function create(string $identifier, array $constructParams = [], bool $shared = true): object
    {
        $className = trim($identifier, "\\");

        if( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        if( ! isset($this->definitions[$className]) ){
            $this->definitions[$className] = new Definition($className);
        }

        $this->definitions[$className]->shared($shared);

        $this->resolver->resolveDependencies($className);
        $object = $this->createObject($className, $constructParams);
        $this->injector->injectDependencies($object);

        if( $shared ) {
            $this->sharedObjects[$className] = $object;
        }

        $this->initializeObject($object);

        return $object;
    }

    /**
     * Adds and register a new shared object to the container. The identifier
     * will be the FQCN of the given object.
     *
     * @param object     $object
     *
     * @return object Returns the given object.
     */
    public function add(object $object): object
    {
        $className = trim(get_class($object), "\\");
        $this->set($className);
        $this->sharedObjects[$className] = $object;
        $this->injector->injectDependencies($object);

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
function set(string $className): Definition
{
    return new Definition($className);
}