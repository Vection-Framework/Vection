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
use Vection\Component\DependencyInjection\Container;
use Vection\Component\DependencyInjection\Definition;
use Vection\Component\DependencyInjection\Resolver;
use Vection\Component\DependencyInjection\Tests\Fixtures\Animal;
use Vection\Component\DependencyInjection\Tests\Fixtures\AnnotationInjectedObject;
use Vection\Component\DependencyInjection\Tests\Fixtures\ExplicitInjectedObject;
use Vection\Component\DependencyInjection\Tests\Fixtures\InterfaceInjectedObject;
use Vection\Component\DependencyInjection\Tests\Fixtures\TestObject;

/**
 * Class ContainerTest
 *
 * @package Vection\Component\DependencyInjection\Tests
 */
class ContainerTest extends TestCase
{

    public function testSetDefaultSuccess(): void
    {
        $dependencies = new ArrayObject();

        $resolver = $this->createMock(Resolver::class);
        $resolver->method('resolveDependencies')->willReturnCallback(static function($className) use (&$dependencies) {
            return $dependencies[$className] = [];
        });

        $container = new Container($resolver);
        $container->set(Animal::class);

        $definitions = $container->getDefinitions();

        $this->assertArrayHasKey(Animal::class, $definitions);
        $this->assertInstanceOf(Definition::class, $definitions[Animal::class]);
        $this->assertArrayHasKey(Animal::class, $dependencies);
        $this->assertIsArray($dependencies[Animal::class]);
    }

    public function testSetWithDefinition(): void
    {
        $dependencies = new ArrayObject();
        $definition = new Definition(Animal::class);

        $resolver = $this->createMock(Resolver::class);
        $resolver->method('resolveDependencies')->willReturnCallback(static function($className) use (&$dependencies) {
            return $dependencies[$className] = [];
        });

        $container = new Container($resolver);
        $container->set(Animal::class, $definition);

        $definitions = $container->getDefinitions();

        $this->assertArrayHasKey(Animal::class, $definitions);
        $this->assertEquals($definition, $definitions[Animal::class]);
        $this->assertArrayHasKey(Animal::class, $dependencies);
        $this->assertIsArray($dependencies[Animal::class]);
    }

    /**
     *
     */
    public function testDependencyContainer()
    {
        $container = new Container();
        $container->load(__DIR__.'/Fixtures/container-conf-1.php');
        $container->setAllowedNamespacePrefixes(['*']);

        // @var TestObject $testObject
        $testObject = $container->get(TestObject::class);

        $this->assertTrue($testObject instanceof TestObject);

        $this->assertTrue($testObject->getExplicitInjectedObject() instanceof ExplicitInjectedObject);
        $this->assertTrue($testObject->getAnnotationInjectedObject() instanceof AnnotationInjectedObject);
        $this->assertTrue($testObject->getInterfaceInjectedObject() instanceof InterfaceInjectedObject);
    }
}
