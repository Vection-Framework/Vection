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

namespace Vection\Contracts\CQRS\Query;

/**
 * Interface QueryHandlerFactoryInterface
 *
 * @package Vection\Contracts\CQRS\Query
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