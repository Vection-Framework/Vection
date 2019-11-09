<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Messenger\Service\Command;

use RuntimeException;
use Vection\Contracts\Messenger\Service\Command\CommandDispatcherInterface;
use Vection\Contracts\Messenger\Service\Command\CommandInterface;

/**
 * Class CommandDispatcher
 *
 * @package Vection\Component\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
class CommandDispatcher implements CommandDispatcherInterface
{
    /**
     * @var callable[]
     */
    protected $handlers = [];

    /**
     * @param string   $commandClassName
     * @param callable $handler
     */
    public function register(string $commandClassName, callable $handler): void
    {
        $this->handlers[$commandClassName] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(CommandInterface $command): void
    {
        $commandClassName = get_class($command);

        if (! isset($this->handlers[$commandClassName])) {
            throw new RuntimeException('Command has no related handler.');
        }

        $this->handlers[$commandClassName]($command);
    }
}
