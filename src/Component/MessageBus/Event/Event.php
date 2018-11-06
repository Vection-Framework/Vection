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

namespace Vection\Component\MessageBus\Event;

use Vection\Component\MessageBus\Message;
use Vection\Contracts\MessageBus\Event\EventInterface;

/**
 * Class Event
 *
 * @package Vection\Component\MessageBus\Event
 */
abstract class Event extends Message implements EventInterface
{

}