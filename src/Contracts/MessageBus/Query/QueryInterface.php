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

namespace Vection\Contracts\MessageBus\Query;

use Vection\Contracts\MessageBus\IdentifiableInterface;
use Vection\Contracts\MessageBus\MessageInterface;

/**
 * Interface QueryInterface
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryInterface extends MessageInterface, IdentifiableInterface
{
    # TYPE SAFETY
}