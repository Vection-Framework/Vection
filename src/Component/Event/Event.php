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

/*
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
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
     * Stops the event propagation if set to true.
     * Use this to abort the event rising.
     *
     * @var boolean
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
