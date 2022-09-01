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

/**
 * Class Injector
 *
 * @package Vection\Component\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Injector
{
    protected Container   $container;
    protected ArrayObject $dependencies;
    /** @var object[] */
    protected array       $infiniteLoopPreventedObjects = [];

    /**
     * Resolver constructor.
     *
     * @param Container $container
     * @param ArrayObject $dependencies
     */
    public function __construct(Container $container, ArrayObject $dependencies)
    {
        $this->container    = $container;
        $this->dependencies = $dependencies;
    }

    /**
     * Injects all dependencies defined by the given object.
     *
     * @param object $object
     */
    public function injectDependencies(object $object): void
    {
        $this->infiniteLoopPreventedObjects[get_class($object)] = $object;

        $this->injectByInterface($object);
        $this->injectByAnnotations($object);
        $this->injectByExplicit($object);

        unset($this->infiniteLoopPreventedObjects[get_class($object)]);

        # If object uses ContainerAwareTrait, inject the container itself
        if ( method_exists($object, '__setContainer') ) {
            $object->__setContainer($this->container);
        }
    }

    /**
     * @param object $object
     */
    protected function injectByInterface(object $object): void
    {
        $id = get_class($object);

        if ( ($this->dependencies[$id]['setter'] ?? null) ) {

            foreach ( $this->dependencies[$id]['setter'] as $setter => $dependency ) {

                if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                    $dependencyObject = $this->infiniteLoopPreventedObjects[$dependency];
                } else {
                    $dependencyObject = $this->container->get($dependency);
                }

                $object->$setter($dependencyObject);
            }

        }
    }

    /**
     * @param object $object
     */
    protected function injectByAnnotations(object $object): void
    {
        $id = get_class($object);

        if ( ($this->dependencies[$id]['annotation'] ?? null) ) {

            $dependencies = [];

            foreach ( $this->dependencies[$id]['annotation'] as $property => $dependency ) {
                if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                    $dependencies[$property] = $this->infiniteLoopPreventedObjects[$dependency];
                } else {
                    $dependencies[$property] = $this->container->get($dependency);
                }
            }

            $object->__annotationInjection($dependencies);
        }
    }

    /**
     * @param object $object
     */
    protected function injectByExplicit(object $object): void
    {
        $id = get_class($object);

        if ( ($this->dependencies[$id]['explicit'] ?? null) ) {

            $dependencies = [];

            foreach ( $this->dependencies[$id]['explicit'] as $dependency ) {
                if (isset($this->infiniteLoopPreventedObjects[$dependency])) {
                    $dependencies[] = $this->infiniteLoopPreventedObjects[$dependency];
                } else {
                    $dependencies[] = $this->container->get($dependency);
                }
            }

            $object->__inject(...$dependencies);
        }
    }
}
