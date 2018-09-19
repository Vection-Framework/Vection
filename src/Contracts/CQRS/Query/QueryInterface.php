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

namespace Vection\Contracts\CQRS\Query;

use Vection\Contracts\CQRS\Common\IdentifiableInterface;
use Vection\Contracts\CQRS\Common\MessageInterface;

/**
 * Interface QueryInterface
 *
 * @package Vection\Contracts\CQRS\Query
 */
interface QueryInterface extends MessageInterface, IdentifiableInterface
{
    # TYPE SAFETY
}