<?php

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\DI\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\DI\Container;
use Vection\Component\DI\Tests\Fixtures\AnnotationInjectedObject;
use Vection\Component\DI\Tests\Fixtures\ExplicitInjectedObject;
use Vection\Component\DI\Tests\Fixtures\InterfaceInjectedObject;
use Vection\Component\DI\Tests\Fixtures\TestObject;

/**
 * Class ContainerTest
 *
 * @package Vection\Component\DI\Tests
 */
class ContainerTest extends TestCase
{
    /**
     *
     */
    public function testDependencyContainer()
    {
        $container = new Container();
        $container->load(__DIR__.'/Fixtures/container-conf-1.php');
        $container->registerNamespace(['*']);

        /** @var TestObject $testObject */
        $testObject = $container->get(TestObject::class);

        $this->assertTrue($testObject instanceof TestObject);

        $this->assertTrue($testObject->getExplicitInjectedObject() instanceof ExplicitInjectedObject);
        $this->assertTrue($testObject->getAnnotationInjectedObject() instanceof AnnotationInjectedObject);
        $this->assertTrue($testObject->getInterfaceInjectedObject() instanceof InterfaceInjectedObject);
    }
}
