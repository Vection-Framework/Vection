<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Tests\Command;

use Vection\Component\CQRS\Command\CommandResolver;
use Vection\Component\CQRS\Tests\Fixtures\TestCommand;
use Vection\Component\CQRS\Tests\Fixtures\TestCommandHandler;

/**
 * Class CommandResolverTest
 *
 * @package Vection\Component\CQRS\Tests\Command
 */
class CommandResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testRegister()
    {
        $resolver = new CommandResolver();
        $resolver->register(TestCommand::class, TestCommandHandler::class);

        # Test should be ok
        $handler = $resolver->resolve(new TestCommand());
        $this->assertTrue($handler instanceof TestCommandHandler);
    }


    public function testResolve()
    {
        $testHandlerMap = [
            TestCommand::class => TestCommandHandler::class
        ];

        $resolver = new CommandResolver(null, $testHandlerMap);

        # Test should be ok
        $handler = $resolver->resolve(new TestCommand());
        $this->assertTrue($handler instanceof TestCommandHandler);
    }
}
