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

namespace Vection\Component\CQRS\Tests\Command;

use Vection\Component\CQRS\Command\CommandBusSequence;
use Vection\Component\CQRS\Tests\Fixtures\InvokableSequenceObject;
use Vection\Component\CQRS\Tests\Fixtures\TestCommand;

/**
 * Class CommandBusSequenceTest
 *
 * @package Vection\Component\CQRS\Tests\Command
 */
class CommandBusSequenceTest extends \PHPUnit_Framework_TestCase
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
