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

namespace Vection\Contracts\CQRS\Event;

use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Interface EventInterface
 *
 * @package Vection\Contracts\CQRS\Event
 */
interface EventInterface extends MessageInterface, \Vection\Contracts\Event\EventInterface
{
    # TYPE SAFETY
}