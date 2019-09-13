<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI\Tests;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Vection\Component\DI\Definition;
use Vection\Component\DI\Resolver;
use Vection\Component\DI\Tests\Fixtures\ConstructorInjectedObject;
use Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject;
use Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObjectInterface;
use Vection\Component\DI\Tests\Fixtures\TestObject;

/**
 * Class ResolverTest
 *
 * @package Vection\Component\DI\Tests
 */
class ResolverTest extends TestCase
{
    /**
     * @throws ReflectionException
     */
    public function testResolveConstructorDependencies() {

        $definitions = new ArrayObject([
            TestObject::class => (new Definition(TestObject::class))
                ->construct(ConstructorInjectedObject::class)
        ]);

        $resolver = new Resolver($definitions, new ArrayObject());

        $dependencies = $resolver->resolveConstructorDependencies(TestObject::class);

        $this->assertEquals(['Vection\Component\DI\Tests\Fixtures\ConstructorInjectedObject'], $dependencies);
    }

    /**
     * @throws ReflectionException
     */
    public function testResolveInterfaceDependencies() {

        $definitions = new ArrayObject([
            InterfaceInjectedObjectInterface::class => (new Definition(InterfaceInjectedObjectInterface::class))
                ->inject(InterfaceInjectedObject::class)
        ]);

        $resolver = new Resolver($definitions, new ArrayObject());

        $dependencies = $resolver->resolveInterfaceDependencies(TestObject::class);

        $this->assertEquals([
            'setInterfaceInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject'
        ], $dependencies);
    }

    /**
     * @throws ReflectionException
     */
    public function testResolveAnnotatedDependencies() {

        $resolver = new Resolver(new ArrayObject(), new ArrayObject());

        $dependencies = $resolver->resolveAnnotatedDependencies(TestObject::class);

        $this->assertEquals([
            'annotationInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject'
        ], $dependencies);
    }

    /**
     * @throws ReflectionException
     */
    public function testResolveExplicitDependencies() {

        $resolver = new Resolver(new ArrayObject(), new ArrayObject());

        $dependencies = $resolver->resolveExplicitDependencies(TestObject::class);

        $this->assertEquals([
            'Vection\Component\DI\Tests\Fixtures\ExplicitInjectedObject'
        ], $dependencies);
    }

    /**
     *
     */
    public function testResolveDependencies()
    {
        $definitions = new ArrayObject();
        $dependencies = new ArrayObject();

        $definitions[TestObject::class] = (new Definition(TestObject::class))
            ->factory(function(){
                return new TestObject(null);
            });

        $definitions[InterfaceInjectedObjectInterface::class] = (new Definition(TestObject::class))
            ->inject(InterfaceInjectedObject::class);


        $resolver = new Resolver($definitions, $dependencies);
        $resolver->resolveDependencies(TestObject::class);

        $expectedConstruct = [
            'Vection\Component\DI\Tests\Fixtures\ConstructorInjectedObject'
        ];

        $expectedSetter = [
            'setInterfaceInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject'
        ];

        $expectedAnnotation = [
            'annotationInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject'
        ];

        $expectedExplicit = [
            'Vection\Component\DI\Tests\Fixtures\ExplicitInjectedObject'
        ];

        $this->assertEquals($expectedConstruct, $dependencies[TestObject::class]['construct']);
        $this->assertEquals($expectedSetter, $dependencies[TestObject::class]['setter']);
        $this->assertEquals($expectedAnnotation, $dependencies[TestObject::class]['annotation']);
        $this->assertEquals($expectedExplicit, $dependencies[TestObject::class]['explicit']);
    }
}
