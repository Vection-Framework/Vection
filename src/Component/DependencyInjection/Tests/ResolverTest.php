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
use Vection\Component\DependencyInjection\Instruction;
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
        $this->assertTrue(true);
    }
}
