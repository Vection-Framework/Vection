<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\MessageBus\Query;

/**
 * Interface QueryCacheHandlerInterface
 *
 * @package Vection\Contracts\MessageBus\Query
 */
interface QueryCacheHandlerInterface
{
    /**
     * This method invalidates the query cache.
     * The cache uses the handler FQCN as cache pool which
     * caches all payload variants of a specific query.
     *
     * @return bool
     */
    public function invalidateCache(): bool;
}