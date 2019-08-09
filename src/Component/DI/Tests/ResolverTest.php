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
use Vection\Component\DI\Definition;
use Vection\Component\DI\Resolver;
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

    public function testResolveDependencies()
    {
        $definitions = new ArrayObject();
        $dependencies = new ArrayObject();

        $definitions[TestObject::class] = (new Definition(TestObject::class))
            ->factory(function(){
                return new TestObject();
            });

        $definitions[InterfaceInjectedObjectInterface::class] = (new Definition(TestObject::class))
            ->inject(InterfaceInjectedObject::class);


        $resolver = new Resolver($definitions, $dependencies);
        $resolver->resolveDependencies(TestObject::class);

        $expectedSetter = [
            'setInterfaceInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject'
        ];

        $expectedAnnotation = [
            'annotationInjectedObject' => 'Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject'
        ];

        $expectedExplicit = [
            'Vection\Component\DI\Tests\Fixtures\ExplicitInjectedObject'
        ];

        $this->assertEmpty($dependencies[TestObject::class]['construct']);

        $this->assertEquals($expectedSetter, $dependencies[TestObject::class]['setter']);
        $this->assertEquals($expectedAnnotation, $dependencies[TestObject::class]['annotation']);
        $this->assertEquals($expectedExplicit, $dependencies[TestObject::class]['explicit']);

    }
}
