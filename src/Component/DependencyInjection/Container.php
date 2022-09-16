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

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Vection\Component\DependencyInjection\Exception\IllegalConstructorParameterException;
use Vection\Component\DependencyInjection\Exception\InvalidArgumentException;
use Vection\Component\DependencyInjection\Exception\NotFoundException;
use Vection\Component\DependencyInjection\Exception\RuntimeException;
use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;
use Vection\Contracts\DependencyInjection\InjectorInterface;
use Vection\Contracts\DependencyInjection\InstructionInterface;
use Vection\Contracts\DependencyInjection\ResolverInterface;

/**
 * Class Container
 *
 * This class provides dependency injection by constructor, interface and annotation definition.
 * An optional configuration file can be used to register
 * and define dependencies.
 *
 * @package Vection\Component\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Container implements ContainerInterface, LoggerAwareInterface, CacheAwareInterface
{
    use LoggerAwareTrait;

    protected ResolverInterface $resolver;
    protected InjectorInterface $injector;
    /** @var string[] */
    protected array $allowedNamespacePrefixes = [];
    /** @var object[] */
    protected array $sharedObjects;

    /**
     * Container constructor.
     *
     * @param ResolverInterface|null $resolver
     * @param Injector|null $injector
     */
    public function __construct(ResolverInterface|null $resolver = null, InjectorInterface|null $injector = null)
    {
        $this->logger = new NullLogger();
        $this->sharedObjects[self::class] = $this;
        $this->resolver     = $resolver ?: new Resolver();
        $this->injector     = $injector ?: new Injector($this, $this->resolver);
    }

    /**
     * @inheritDoc
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->resolver->setCache($cache->getPool('DIC'));
    }

    /**
     * All objects of the given namespaces will be auto registered at runtime
     * for injection into other objects without the need to define them
     * in the config or by set/add methods. Pass ['*'] as wildcard to register all namespaces.
     *
     * @param string[] $namespacePrefixes
     */
    public function setAllowedNamespacePrefixes(array $namespacePrefixes): void
    {
        $this->allowedNamespacePrefixes = $namespacePrefixes;
    }

    /**
     * Loads an instructions file, which contains the
     * instructions for the classes that will be handled by the
     * container. This method uses glob to support paths with wildcards
     * and dynamic path types that are supported by glob too. This gives the possibility
     * to add multiple files.
     *
     * @param string $path
     */
    public function load(string $path): void
    {
        if ( ($pathArray = glob($path)) === false ) {
            throw new InvalidArgumentException("Given path is not valid or doesn't exists.");
        }

        foreach ( $pathArray as $filePath ) {
            // @noinspection PhpIncludeInspection
            $instructions = require $filePath;

            if ( ! is_array($instructions) ) {
                throw new RuntimeException("Cannot load instruction from $filePath.");
            }

            foreach ( $instructions as $instruction ) {
                if ( ! $instruction instanceof InstructionInterface ) {
                    throw new RuntimeException(
                        'Invalid configuration file: Each entry must be of type InstructionInterface.'
                    );
                }

                $this->resolver->addInstruction($instruction);
            }
        }
    }

    /**
     * @param string  $className
     * @param array<int, mixed> $constructParams
     *
     * @return object
     */
    private function createObject(string $className, array $constructParams = []): object
    {
        $factory = $this->resolver->getInstruction($className)?->getFactory();

        if ($constructParams && !$factory) {
            return new $className(...$constructParams);
        }

        if ($factory) {
            return $factory($this, ...$constructParams);
        }

        $dependencies = $this->resolver->getClassDependencies($className);

        if ( $constructParams = $dependencies['constructor'] ) {
            $paramObjects = [];
            $nullableInterfaces = $dependencies['constructor_nullable_interface'] ?? [];
            $preventInjectionParams = $dependencies['constructor_prevent_injection'] ?? [];

            foreach ( $constructParams as $param ) {
                $isNullableInterface = in_array($param, $nullableInterfaces, true);
                $isPreventInjectionParam = in_array($param, $preventInjectionParams, true);
                if ($isPreventInjectionParam || ($isNullableInterface && !$this->resolver->getInstruction($param))) {
                    $paramObjects[] = null;
                }else{
                    // @phpstan-ignore-next-line
                    $paramObjects[] = $this->get($param);
                }
            }
            return new $className(...$paramObjects);
        }

        if (isset($dependencies['constructor_has_primitives']) && count($constructParams) === 0) {
            throw new IllegalConstructorParameterException(
                'The use of primitive parameter types at constructor injection can only be used when '.
                'creating object with explicit construct parameters e.g. '.
                'Container::create(class, [param1, param2,..])), occurred in class '.$className
            );
        }

        return new $className();
    }

    /**
     * @param string $id
     *
     * @return bool
     */
    private function evaluate(string $id): bool
    {
        # Check if there is a defined entry for the given id
        if (!$this->has($id)) {

            if ($this->allowedNamespacePrefixes) {
                # Check if the id is part of a registered namespace scope
                foreach ($this->allowedNamespacePrefixes as $namespace) {
                    if (str_starts_with($id, $namespace)) {
                        # The id matches a scope, so we allow to register a default instruction for this id
                        $this->set($id);
                        return true;
                    }
                }

                return false;
            }

            $this->set($id);
            return true;
        }

        return true;
    }

    /**
     * @param object $object
     */
    private function initializeObject(object $object): void
    {
        if ( method_exists($object, '__init') && is_callable($object->__init()) ) {
             $object->__init();
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
    public function has(string $id): bool
    {
        return (bool) $this->resolver->getInstruction(trim($id, "\\"));
    }

    /**
     * Register a new entry by given identifier. The second parameter can be used
     * for entry instruction
     *
     * @param string                    $className
     * @param InstructionInterface|null $instruction
     *
     * @return Container
     */
    public function set(string $className, InstructionInterface|null $instruction = null): Container
    {
        $className = trim($className, "\\");
        $this->resolver->addInstruction($instruction ?: new Instruction($className));
        $this->resolver->resolveDependencies($className);

        return $this;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     * If the requested entry has dependencies, these will be injected before
     * return this object.
     *
     * @template T of object
     *
     * @param class-string<T> $id Identifier of the entry to look for.
     *
     * @return T
     */
    public function get(string $id): object
    {
        $className = ltrim($id, "\\");

        if ( isset($this->sharedObjects[$className]) ) {
            return $this->sharedObjects[$className];
        }

        if ( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        $this->resolver->resolveDependencies($className);
        $object = $this->createObject($className);
        $this->injector->injectDependencies($object);

        if ( ($instruction = $this->resolver->getInstruction($className)) && $instruction->isShared()) {
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
     * @template T of object
     *
     * @param class-string<T>   $identifier      The identifier of registered entry.
     * @param array<int, mixed> $constructParams Parameter that should be passed to constructor.
     * @param bool              $shared          Whether the new object should be shared or not.
     *
     * @return T The new created object.
     */
    public function create(string $identifier, array $constructParams = [], bool $shared = true): object
    {
        $className = trim($identifier, "\\");

        if ( ! $this->evaluate($className) ) {
            throw new NotFoundException('DI Container: Unregistered identifier: '.$className);
        }

        $instruction = $this->resolver->getInstruction($className);

        if (!$instruction) {
            $this->resolver->addInstruction($instruction = new Instruction($className));
        }

        $instruction->asShared($shared);

        $this->resolver->resolveDependencies($className);
        $object = $this->createObject($className, $constructParams);
        $this->injector->injectDependencies($object);

        if ( $shared ) {
            $this->sharedObjects[$className] = $object;
        }

        $this->initializeObject($object);

        return $object;
    }

    /**
     * Adds and register a new shared object to the container. The identifier
     * will be the FQCN of the given object.
     *
     * @template T of object
     *
     * @param T $object
     *
     * @return T Returns the given object.
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
 * Returns a new instance of InstructionInterface.
 *
 * @param string $className
 *
 * @return Instruction
 */
function resolve(string $className): InstructionInterface
{
    return new Instruction($className);
}
