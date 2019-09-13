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

namespace Vection\Component\MessageBus\Tests\Command;

use Vection\Component\MessageBus\Command\CommandResolver;
use Vection\Component\MessageBus\Tests\Fixtures\TestCommand;
use Vection\Component\MessageBus\Tests\Fixtures\TestCommandHandler;
use PHPUnit\Framework\TestCase;
/**
 * Class CommandResolverTest
 *
 * @package Vection\Component\MessageBus\Tests\Command
 */
class CommandResolverTest extends TestCase
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
