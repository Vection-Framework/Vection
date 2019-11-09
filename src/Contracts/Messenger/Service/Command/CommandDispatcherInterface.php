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

namespace Vection\Contracts\Messenger\Service\Command;

/**
 * Interface CommandDispatcherInterface
 *
 * @package Vection\Contracts\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface CommandDispatcherInterface
{
    /**
     * Dispatch and executes the command related handler.
     *
     * @param CommandInterface $command
     */
    public function dispatch(CommandInterface $command): void;
}
