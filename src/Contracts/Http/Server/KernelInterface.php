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

declare(strict_types=1);

namespace Vection\Contracts\Http\Server;

use Psr\Log\LoggerAwareInterface;
use Vection\Contracts\Event\EventManagerInterface;

/**
 * Interface KernelInterface
 *
 * @package Vection\Contracts\Http\Server
 */
interface KernelInterface extends LoggerAwareInterface
{
    /**
     * Returns the responder that is used to send a response to the client.
     *
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface;

    /**
     * Sets an event manager which fires events on several execution steps.
     *
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager): void;

    /**
     * Executes the kernel.
     *
     * Set true to terminate the current process after send the response to client.
     *
     * @param bool $terminate
     */
    public function execute(bool $terminate = true): void;
}
