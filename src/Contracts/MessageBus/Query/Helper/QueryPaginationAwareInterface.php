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
 * Interface QueryPaginationAwareInterface
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
interface QueryPaginationAwareInterface
{
    /**
     * @return QueryPaginationInterface
     */
    public function getPagination(): QueryPaginationInterface;

    /**
     * @param QueryPaginationInterface $pagination
     */
    public function setPagination(QueryPaginationInterface $pagination): void;
}