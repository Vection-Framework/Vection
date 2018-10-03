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

namespace Vection\Component\CQRS\Event;

use Vection\Component\CQRS\Common\Message;
use Vection\Contracts\CQRS\Event\EventInterface;

/**
 * Class Event
 *
 * @package Vection\Component\CQRS\Event
 */
abstract class Event extends Message implements EventInterface
{

}