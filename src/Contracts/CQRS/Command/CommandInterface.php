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

namespace Vection\Contracts\CQRS\Command;

use Vection\Contracts\CQRS\Common\IdentifiableInterface;
use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Interface CommandInterface
 *
 * @package Vection\Contracts\CQRS\Command
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