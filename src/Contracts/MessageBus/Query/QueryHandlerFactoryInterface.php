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
 * Interface QueryHandlerFactoryInterface
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryHandlerFactoryInterface
{
    /**
     * Creates a new instance of QueryHandlerInterface.
     *
     * @param string $className
     *
     * @return QueryHandlerInterface
     */
    public function create(string $className): QueryHandlerInterface;
}
