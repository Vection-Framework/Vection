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

namespace Vection\Component\Event;

use Vection\Contracts\Event\EventInterface;

/**
 * Class Event
 *
 * @package Vection\Component\Event
 */
abstract class Event implements EventInterface
{

    /**
     * @var bool
     */
    protected $propagationStopped = false;

    /**
     * @inheritDoc
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }

    /**
     * @inheritDoc
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

}