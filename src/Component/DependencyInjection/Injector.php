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

use Vection\Contracts\DependencyInjection\InjectorInterface;
use Vection\Contracts\DependencyInjection\ResolverInterface;

/**
 * Class Injector
 *
 * @package Vection\Component\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Injector implements InjectorInterface
{
    protected Container $container;
    /** @var object[] */
    protected array             $infiniteLoopPreventedObjects = [];
    protected ResolverInterface $resolver;

    /**
     * Injector constructor.
     *
     * @param Container         $container
     * @param ResolverInterface $resolver
     */
    public function __construct(Container $container, ResolverInterface $resolver)
    {
        $this->container = $container;
        $this->resolver  = $resolver;
    }

    /**
     * Injects all dependencies defined by the given object.
     *
     * @param object $object
     */
    public function injectDependencies(object $object): void
    {
        $this->infiniteLoopPreventedObjects[get_class($object)] = $object;

        $this->injectViaSetter($object);
        $this->injectViaAnnotations($object);
        $this->injectViaMagic($object);

        unset($this->infiniteLoopPreventedObjects[get_class($object)]);

        # If object uses ContainerAwareTrait, inject the container itself
        if ( method_exists($object, '__setContainer') ) {
            $object->__setContainer($this->container);
        }
    }

    /**
     * @param object $object
     */
    protected function injectViaSetter(object $object): void
    {
        $id = get_class($object);

        $classDependencies = $this->resolver->getClassDependencies($id);

        foreach ($classDependencies['setter'] ?? [] as $setter => $dependency) {

            if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                $dependencyObject = $this->infiniteLoopPreventedObjects[$dependency];
            } else {
                // @phpstan-ignore-next-line
                $dependencyObject = $this->container->get($dependency);
            }

            $object->$setter($dependencyObject);
        }
    }

    /**
     * @param object $object
     */
    protected function injectViaAnnotations(object $object): void
    {
        $id = get_class($object);

        $dependencies = [];
        $classDependencies = $this->resolver->getClassDependencies($id);

        foreach ($classDependencies['annotation'] ?? [] as $property => $dependency ) {
            if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                $dependencies[$property] = $this->infiniteLoopPreventedObjects[$dependency];
            } else {
                // @phpstan-ignore-next-line
                $dependencies[$property] = $this->container->get($dependency);
            }
        }

        $object->__annotationInjection($dependencies);
    }

    /**
     * @param object $object
     */
    protected function injectViaMagic(object $object): void
    {
        $id = get_class($object);

        $classDependencies = $this->resolver->getClassDependencies($id);

        if ($classDependencies['magic'] ?? null) {

            $dependencies = [];

            foreach ( $classDependencies['magic'] as $dependency ) {
                if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                    $dependencies[] = $this->infiniteLoopPreventedObjects[$dependency];
                } else {
                    // @phpstan-ignore-next-line
                    $dependencies[] = $this->container->get($dependency);
                }
            }

            $object->__inject(...$dependencies);
        }
    }
}
