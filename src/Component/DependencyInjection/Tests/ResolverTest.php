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

namespace Vection\Component\DependencyInjection\Tests;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use Vection\Component\DependencyInjection\Definition;
use Vection\Component\DependencyInjection\Resolver;
use Vection\Component\DependencyInjection\Tests\Fixtures\ConstructorInjectedObject;
use Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObject;
use Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObjectInterface;
use Vection\Component\DependencyInjection\Tests\Fixtures\TestObject;

/**
 * Class ResolverTest
 *
 * @package Vection\Component\DependencyInjection\Tests
 */
class ResolverTest extends TestCase
{
    public function testResolveDependencies(): void
    {
        $definitions  = new ArrayObject();
        $dependencies = new ArrayObject();

        $definitions[TestObject::class] = (new Definition(TestObject::class))
            ->factory(
                function(){
                    return new TestObject(new ConstructorInjectedObject());
                }
            );

        $definitions[InterfaceInjectedObjectInterface::class] = (new Definition(TestObject::class))
            ->inject(InterfaceInjectedObject::class);

        $resolver = new Resolver($definitions, $dependencies);
        $resolver->resolveDependencies(TestObject::class);

        $expectedConstruct = [
            # Empty because of defined factory method and manual instantiation and null param
        ];

        $expectedSetter = [
            'setInterfaceInjectedObject' => 'Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObject'
        ];

        $expectedAnnotation = [
            'annotationInjectedObject' => 'Vection\Component\DependencyInjection\Tests\Fixtures\AnnotationInjectedObject'
        ];

        $expectedExplicit = [
            'Vection\Component\DependencyInjection\Tests\Fixtures\ExplicitInjectedObject'
        ];

        $this->assertEquals($expectedConstruct, $dependencies[TestObject::class]['construct']);
        $this->assertEquals($expectedSetter, $dependencies[TestObject::class]['setter']);
        $this->assertEquals($expectedAnnotation, $dependencies[TestObject::class]['annotation']);
        $this->assertEquals($expectedExplicit, $dependencies[TestObject::class]['magic']);
    }
}
