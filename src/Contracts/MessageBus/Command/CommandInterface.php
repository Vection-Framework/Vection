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

namespace Vection\Contracts\MessageBus\Command;

use Vection\Contracts\MessageBus\IdentifiableInterface;
use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Interface CommandInterface
 *
 * @package Vection\Contracts\MessageBus\Command
 */
interface CommandInterface extends MessageInterface, IdentifiableInterface
{
    /**
     * Returns true if this command uses a bus
     * which executes the handler within a transaction.
     *
     * @return bool
     */
    public function isTransactional(): bool;
}