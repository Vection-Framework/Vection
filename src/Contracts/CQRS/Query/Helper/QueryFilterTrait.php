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
 * Trait QueryFilterTrait
 *
 * @package Vection\Contracts\CQRS\Query\Helper
 */
trait QueryFilterTrait
{
    /** @var QueryFilterInterface */
    private $filter;

    /**
     * @return QueryFilterInterface
     */
    public function getFilter(): QueryFilterInterface
    {
        return $this->filter;
    }

    /**
     * @param QueryFilterInterface $filter
     */
    public function setFilter(QueryFilterInterface $filter): void
    {
        $this->filter = $filter;
    }

}