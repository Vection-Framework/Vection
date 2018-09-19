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

namespace Vection\Component\CQRS\Tests\Fixtures;
use Vection\Contracts\CQRS\Command\CommandBusMiddlewareInterface;
use Vection\Contracts\CQRS\Command\CommandBusSequenceInterface;
use Vection\Contracts\CQRS\Command\CommandInterface;

/**
 * Class CommandTestBus
 *
 * @package Vection\Component\CQRS\Tests\Fixtures
 */
class CommandTestBus implements CommandBusMiddlewareInterface
{

    /**
     * This method executes the middleware specific logic.
     *
     * @param CommandInterface $command
     * @param CommandBusSequenceInterface $sequence
     */
    public function __invoke(CommandInterface $command, CommandBusSequenceInterface $sequence)
    {

    }
}