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
 * Trait QueryPaginationTrait
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
trait QueryPaginationTrait
{
    /** @var QueryPaginationInterface */
    private $pagination;

    /**
     * @return QueryPaginationInterface
     */
    public function getPagination(): QueryPaginationInterface
    {
        return $this->pagination;
    }

    /**
     * @param QueryPaginationInterface $pagination
     */
    public function setPagination(QueryPaginationInterface $pagination): void
    {
        $this->pagination = $pagination;
    }
}