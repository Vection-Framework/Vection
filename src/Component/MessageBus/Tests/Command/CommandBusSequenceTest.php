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

use Vection\Component\MessageBus\Command\CommandBusSequence;
use Vection\Component\MessageBus\Tests\Fixtures\InvokableSequenceObject;
use Vection\Component\MessageBus\Tests\Fixtures\TestCommand;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandBusSequenceTest
 *
 * @package Vection\Component\MessageBus\Tests\Command
 */
class CommandBusSequenceTest extends TestCase
{
    /**
     *
     */
    public function testInvokeNext()
    {
        $testCommand = new TestCommand();

        $ISO1 = new InvokableSequenceObject();
        $ISO2 = new InvokableSequenceObject();

        $sequence = new CommandBusSequence([$ISO1, $ISO2]);

        # First sequence invoke
        $sequence->invokeNext($testCommand);
        $this->assertTrue($ISO1->isInvoked() && ! $ISO2->isInvoked());

        # Second sequence invoke
        $sequence->invokeNext($testCommand);
        $this->assertTrue($ISO1->isInvoked() && $ISO2->isInvoked());
    }
}
