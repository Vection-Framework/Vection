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

namespace Vection\Contracts\CQRS\Query\Helper;

/**
 * Interface QueryPaginationInterface
 *
 * @package Vection\Contracts\CQRS\Query\Helper
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