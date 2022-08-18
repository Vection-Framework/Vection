<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Contracts\Http\Server;

use Psr\Log\LoggerAwareInterface;
use Vection\Contracts\Event\EventDispatcherInterface;

/**
 * Interface KernelInterface
 *
 * @package Vection\Contracts\Http\Server
 *
 * @author  David M. Lung <vection@davidlung.de>
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
     * Sets an event dispatcher which fires events on several execution steps.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void;

    /**
     * Executes the kernel.
     *
     * Set true to terminate the current process after send the response to client.
     *
     * @param bool $terminate
     * @param bool $clearUnexpectedBuffer
     */
    public function execute(bool $terminate = true, bool $clearUnexpectedBuffer = true): void;
}
