<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\MessageBus\Query\Helper;

/**
 * Trait QueryOrderTrait
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
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
