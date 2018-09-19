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

/**
 * Interface CommandTransactionInterface
 *
 * @package Vection\Contracts\CQRS\Command
 */
interface CommandTransactionInterface
{
    /**
     * Begins a new transaction.
     */
    public function begin();

    /**
     * Commits the current transaction.
     */
    public function commit();

    /**
     * Resets all changes made.
     */
    public function rollback();
}