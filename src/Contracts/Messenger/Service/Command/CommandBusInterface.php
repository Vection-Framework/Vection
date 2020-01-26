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

use Vection\Contracts\Messenger\MessageRelationInterface;

/**
 * Interface CommandBusInterface
 *
 * @package Vection\Contracts\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface CommandBusInterface
{
    /**
     * Pass the command to the message bus.
     * This method provides only a wrapped dispatching of the
     * message bus by creating a message with the given command as
     * payload and pass to the message bus instance. So make sure
     * the message bus has the correct setup and contains a related
     * command dispatcher middleware.
     *
     * @param object                        $command
     * @param MessageRelationInterface|null $relation
     *
     * @see CommandHandlerMiddleware
     */
    public function execute(object $command, ?MessageRelationInterface $relation = null): void;
}
