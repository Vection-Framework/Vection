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
use Vection\Component\DependencyInjection\Instruction;
use Vection\Component\DependencyInjection\Resolver;
use Vection\Contracts\DependencyInjection\InstructionInterface;

/**
 * Class ResolverTest
 *
 * @package Vection\Component\DependencyInjection\Tests
 */
class ResolverTest extends TestCase
{
    public function testAddInstruction(): void
    {
        $instructions = new ArrayObject();
        $resolver = new Resolver($instructions);

        $instructionMock = $this->createMock(Instruction::class);
        $instructionMock->method('getClassName')->willReturn('test\\TestClass');

        $resolver->addInstruction($instructionMock);

        $this->assertArrayHasKey('test\\TestClass', $instructions);
        $this->assertEquals($instructionMock, $instructions['test\\TestClass']);
    }

    public function testGetInstruction(): void
    {
        $instructionMock = $this->createMock(Instruction::class);

        /** @var ArrayObject<string, InstructionInterface> $instructions */
        $instructions = new ArrayObject([
            'test\\TestClass' => $instructionMock
        ]);

        $resolver = new Resolver($instructions);

        $this->assertEquals($instructionMock, $resolver->getInstruction('test\\TestClass'));
    }

    public function testGetClassDependencies(): void
    {
        /** @var ArrayObject<string, mixed> $dependencies */
        $dependencies = new ArrayObject([
            'test\\TestClass' => [
                'test' => 'works'
            ]
        ]);

        $resolver = new Resolver(dependencies: $dependencies);
        $classDependencies = $resolver->getClassDependencies('test\\TestClass');

        $this->assertArrayHasKey('test', $classDependencies);
        $this->assertArrayHasKey('test', $classDependencies);
        $this->assertEquals('works', $classDependencies['test']);
    }

    public function testResolveDependenciesAnnotationInjectionSucceeded(): void
    {
        $resolver = new Resolver();
        $dependencies = $resolver->resolveDependencies(Fixtures\AnnotationInjection\Vehicle::class);

        $this->assertArrayHasKey('engine', $dependencies['annotation']);
        $this->assertArrayHasKey('transmission', $dependencies['annotation']);

        $this->assertEquals(Fixtures\AnnotationInjection\Engine::class, $dependencies['annotation']['engine']);
        $this->assertEquals(Fixtures\AnnotationInjection\Transmission::class, $dependencies['annotation']['transmission']);

        $dependencies = $resolver->resolveDependencies(Fixtures\AnnotationInjection\Engine::class);

        $this->assertArrayHasKey('fuel', $dependencies['annotation']);
        $this->assertEquals(Fixtures\AnnotationInjection\Fuel::class, $dependencies['annotation']['fuel']);

        $dependencies = $resolver->resolveDependencies(Fixtures\AnnotationInjection\Truck::class);

        $this->assertArrayHasKey('trailer', $dependencies['annotation']);
        $this->assertEquals(Fixtures\AnnotationInjection\Trailer::class, $dependencies['annotation']['trailer']);
    }

    public function testResolveDependenciesConstructorInjectionSucceeded(): void
    {
        $resolver = new Resolver();
        $dependencies = $resolver->resolveDependencies(Fixtures\ConstructorInjection\Vehicle::class);

        $this->assertContains(Fixtures\ConstructorInjection\Engine::class, $dependencies['constructor']);
        $this->assertContains(Fixtures\ConstructorInjection\Transmission::class, $dependencies['constructor']);

        $dependencies = $resolver->resolveDependencies(Fixtures\ConstructorInjection\Truck::class);

        $this->assertContains(Fixtures\ConstructorInjection\Engine::class, $dependencies['constructor']);
        $this->assertContains(Fixtures\ConstructorInjection\Transmission::class, $dependencies['constructor']);
    }

    public function testResolveDependenciesFactory(): void
    {
        $instructionMock = $this->createMock(InstructionInterface::class);
        $instructionMock->method('getClassName')->willReturn(Fixtures\Factory\Vehicle::class);
        $instructionMock->method('getFactory')->willReturn($callable = function() {
            return new Fixtures\Factory\Vehicle(
                $this->createMock(Fixtures\Factory\Engine::class),
                $this->createMock(Fixtures\Factory\Transmission::class),
            );
        });

        $resolver = new Resolver();
        $resolver->addInstruction($instructionMock);
        $dependencies = $resolver->resolveDependencies(Fixtures\Factory\Vehicle::class);

        $this->assertEquals($callable, $dependencies['factory']);
    }
}
