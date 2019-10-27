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

namespace Vection\Component\MessageBus\Query\Helper;

use Vection\Contracts\MessageBus\Query\Helper\QueryPaginationInterface;

/**
 * Class QueryPagination
 *
 * @package Vection\Component\MessageBus\Query\Helper
 */
class QueryPagination implements QueryPaginationInterface
{

    /** @var integer */
    private $count;

    /** @var integer */
    private $start;

    /**
     * QueryPagination constructor.
     *
     * @param int $count
     * @param int $start
     */
    public function __construct(int $count = 20, int $start = 0)
    {
        $this->count = $count;
        $this->start = $start;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return int
     */
    public function getStart(): int
    {
        return $this->start;
    }

    /**
     * @param int $start
     */
    public function setStart(int $start): void
    {
        $this->start = $start;
    }

}
