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

use Vection\Component\CQRS\Command\CommandBus;
use Vection\Component\CQRS\Tests\Fixtures\CommandTestBus;
use Vection\Component\CQRS\Tests\Fixtures\TestCommand;

class CommandBusTest extends \PHPUnit_Framework_TestCase
{

    public function testExecute()
    {
        $commandBus = new CommandBus();
        $commandBus->attach(new CommandTestBus());
        $commandBus->execute(new TestCommand());
    }
}
