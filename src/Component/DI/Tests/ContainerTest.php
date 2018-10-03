<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI\Tests;

use Vection\Component\DI\Container;
use Vection\Component\DI\Tests\Fixtures\TestObject;

/**
 * Class ContainerTest
 *
 * @package Vection\Component\DI\Tests
 */
class ContainerTest extends \PHPUnit_Framework_TestCase
{

    public function testCase()
    {
        $container = new Container();

        $container->load(__DIR__.'/Fixtures/container-conf-1.php');

        /** @var TestObject $object */
        $object = $container->get(TestObject::class);

        $this->assertTrue($object->instantiatedByDef());
    }
}
