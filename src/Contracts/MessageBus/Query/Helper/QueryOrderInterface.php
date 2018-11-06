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

namespace Vection\Contracts\MessageBus\Query\Helper;

/**
 * Interface QueryOrderInterface
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
interface QueryOrderInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return int
     */
    public function getDirection(): int;

    /**
     * @return string
     */
    public function getDirectionString(): string;

}