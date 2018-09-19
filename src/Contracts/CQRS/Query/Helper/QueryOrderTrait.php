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
 * Trait QueryOrderTrait
 *
 * @package Vection\Contracts\CQRS\Query\Helper
 */
trait QueryOrderTrait
{
    /** @var QueryOrderInterface */
    private $order;

    /**
     * @return QueryOrderInterface
     */
    public function getOrder(): QueryOrderInterface
    {
        return $this->order;
    }

    /**
     * @param QueryOrderInterface $order
     */
    public function setOrder(QueryOrderInterface $order): void
    {
        $this->order = $order;
    }

}