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
 * Interface QueryPaginationAwareInterface
 *
 * @package Vection\Contracts\CQRS\Query\Helper
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