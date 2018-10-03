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

namespace Vection\Contracts\Cache;

/**
 * Interface CacheInterface
 *
 * @package Vection\Contracts\Cache
 */
interface CacheInterface extends CacheProviderInterface
{

    /**
     * Returns the cache key namespace.
     *
     * @return string
     */
    public function getNamespace(): string;

    /**
     * Returns a Cache instance with the given namespace
     * which separates its items from the other cache instances.
     * If the cache pool is not existing yet, it will create a new
     * cache instance with a namespace that consists of the parent
     * pool and the given separated by ":".
     *
     * @param string $namespace
     *
     * @return CacheInterface
     */
    public function getPool(string $namespace): CacheInterface;

}