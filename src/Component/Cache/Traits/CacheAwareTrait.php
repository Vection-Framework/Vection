<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Cache\Traits;

use Vection\Component\Cache\Cache;
use Vection\Component\Cache\Provider\ArrayCacheProvider;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Trait CacheAwareTrait
 *
 * This trait extends a class by a cache instance with its set and get
 * methods. It also operate as a supplement for classes which
 * implements the CacheAwareInterface.
 *
 * @see \Vection\Contracts\Cache\CacheAwareInterface
 * @see \Vection\Contracts\Cache\CacheInterface
 *
 * @package Vection\Component\Cache\Traits
 *
 * @author  David Lung <vection@davidlung.de>
 */
trait CacheAwareTrait
{

    /**
     * An instance of Cache.
     *
     * @var CacheInterface
     */
    protected CacheInterface $cache;

    /**
     * Returns the cache instance if exists or null otherwise.
     *
     * @return CacheInterface
     */
    public function getCache(): CacheInterface
    {
        return ($this->cache ?: ($this->cache = new Cache(new ArrayCacheProvider())));
    }

    /**
     * Sets a cache instance to this object.
     *
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

}
