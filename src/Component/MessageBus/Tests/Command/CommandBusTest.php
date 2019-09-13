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

use Vection\Component\MessageBus\Command\CommandBus;
use Vection\Component\MessageBus\Tests\Fixtures\CommandTestBus;
use Vection\Component\MessageBus\Tests\Fixtures\TestCommand;
use PHPUnit\Framework\TestCase;

class CommandBusTest extends TestCase
{

    public function testExecute()
    {
        $commandBus = new CommandBus();
        $commandBus->attach(new CommandTestBus());
        $commandBus->execute(new TestCommand());

        $this->assertTrue(true);
    }
}
