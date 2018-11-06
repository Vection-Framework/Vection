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
 * Interface QueryFilterAwareInterface
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
interface QueryFilterAwareInterface
{
    /**
     * @return QueryFilterInterface
     */
    public function getFilter(): QueryFilterInterface;

    /**
     * @param QueryFilterInterface $filter
     */
    public function setFilter(QueryFilterInterface $filter): void;
}