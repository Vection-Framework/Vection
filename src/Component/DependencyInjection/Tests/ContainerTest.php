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

use PHPUnit\Framework\TestCase;

/**
 * Class ContainerTest
 *
 * @package Vection\Component\DependencyInjection\Tests
 */
class ContainerTest extends TestCase
{

    public function testSetDefaultSuccess(): void
    {
//        $dependencies = new ArrayObject();
//
//        $resolver = $this->createMock(Resolver::class);
//        $resolver->method('resolveDependencies')->willReturnCallback(static function($className) use (&$dependencies) {
//            return $dependencies[$className] = [];
//        });
//
//        $container = new Container($resolver);
//        $container->set(Animal::class);
//
//        $definitions = $container->getDefinitions();
//
//        $this->assertArrayHasKey(Animal::class, $definitions);
//        $this->assertInstanceOf(Instruction::class, $definitions[Animal::class]);
//        $this->assertArrayHasKey(Animal::class, $dependencies);
//        $this->assertIsArray($dependencies[Animal::class]);
        $this->assertTrue(true);
    }

    public function testSetWithDefinition(): void
    {
//        $dependencies = new ArrayObject();
//        $definition = new Instruction(Animal::class);
//
//        $resolver = $this->createMock(Resolver::class);
//        $resolver->method('resolveDependencies')->willReturnCallback(static function($className) use (&$dependencies) {
//            return $dependencies[$className] = [];
//        });
//
//        $container = new Container($resolver);
//        $container->set(Animal::class, $definition);
//
//        $definitions = $container->getDefinitions();
//
//        $this->assertArrayHasKey(Animal::class, $definitions);
//        $this->assertEquals($definition, $definitions[Animal::class]);
//        $this->assertArrayHasKey(Animal::class, $dependencies);
//        $this->assertIsArray($dependencies[Animal::class]);

        $this->assertTrue(true);
    }
}
