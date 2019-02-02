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

namespace Vection\Component\MessageBus\Tests\Fixtures;


use Vection\Contracts\MessageBus\Command\CommandHandlerInterface;

/**
 * Class TestCommandHandler
 *
 * @package Vection\Component\MessageBus\Tests\Fixtures
 */
class TestCommandHandler implements CommandHandlerInterface
{

    public function __invoke(TestCommand $message)
    {

    }
}