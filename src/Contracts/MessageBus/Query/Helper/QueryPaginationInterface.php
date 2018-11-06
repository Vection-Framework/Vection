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
 * Interface QueryPaginationInterface
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
interface QueryPaginationInterface
{
    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return int
     */
    public function getStart(): int;

    /**
     * @param int $count
     */
    public function setCount(int $count): void;

    /**
     * @param int $start
     */
    public function setStart(int $start): void;

}