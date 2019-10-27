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

namespace Vection\Contracts\MessageBus\Query;

/**
 * Interface QueryResolverInterface
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryResolverInterface
{
    /**
     * Returns a query handler related to the given
     * query object.
     *
     * @param QueryInterface $query
     *
     * @return QueryHandlerInterface
     */
    public function resolve(QueryInterface $query): QueryHandlerInterface;
}
