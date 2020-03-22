<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Event;

use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Interface EventInterface
 *
 * @package Vection\Contracts\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EventInterface extends StoppableEventInterface
{
    /**
     * Stops the propagation of the event to further event handlers.
     */
    public function stopPropagation();

    /**
     * Returns true if the further propagation has been stopped
     * otherwise this method returns false.
     *
     * @return bool Whether the propagation was already stopped.
     */
    public function isPropagationStopped(): bool;

}
