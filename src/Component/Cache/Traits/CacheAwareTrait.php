<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Cache\Traits;

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
 */
trait CacheAwareTrait
{
    /**
     * An instance of Cache.
     *
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Returns the cache instance if exists or null otherwise.
     *
     * @return null|CacheInterface
     */
    public function getCache(): ? CacheInterface
    {
        return $this->cache;
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