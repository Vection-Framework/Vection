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

namespace Vection\Component\Cache;

use Vection\Contracts\Cache\CacheInterface;
use Vection\Contracts\Cache\CacheProviderInterface;

/**
 * Class Cache
 *
 * @package Vection\Component\Cache
 */
class Cache implements CacheInterface
{
    /**
     * Each cache instance can have a specific key namespace
     * to separate the values from other cache instances.
     * This make it possible to manage different cache domains
     * in multiple nested levels.
     *
     * @var string
     */
    protected $namespace;

    /**
     * A string that represents the separator between nested
     * caching pools. E.g. when using the default separator ":"
     * the namespaces would be "Vection-Cache:customPoolName:etc".
     *
     * @var string
     */
    protected $nsSeparator;

    /**
     * The specific cache provider. This cache class delegates
     * all methods defined by CacheProviderInterface to this provider.
     *
     * @var CacheProviderInterface
     */
    protected $cacheProvider;

    /**
     * Pools are used for different Cache instances which can be
     * managed in multiple nested levels. Each pool is a children
     * Cache object of the Cache which has created the pool.
     * Pools are aligned in a hierarchical structure.
     *
     * @var CacheInterface[]
     */
    protected $pools;

    /**
     * Cache constructor.
     *
     * @param CacheProviderInterface $cacheProvider
     * @param string $namespace
     * @param string $nsSeparator
     */
    public function __construct(CacheProviderInterface $cacheProvider, string $namespace = '', string $nsSeparator = '')
    {
        $this->cacheProvider = $cacheProvider;
        $this->namespace = $namespace ?: 'Vection-Cache';
        $this->nsSeparator = $nsSeparator ?: ':';
    }

    /**
     * @inheritdoc
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @inheritdoc
     */
    public function getPool(string $namespace): CacheInterface
    {
        if ( ! isset($this->pools[$namespace]) ) {
            # Create and save new Cache pool with extended pool namespace
            $this->pools[$namespace] =  new Cache(
                $this->cacheProvider, $this->namespace . $this->nsSeparator . $namespace, $this->nsSeparator
            );
        }

        return $this->pools[$namespace];
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        return $this->cacheProvider->clear($this->getNSKey($namespace));
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getNSKey(string $key): string
    {
        return $this->namespace . ( $key ? $this->nsSeparator . $key : '' );
    }

    /**
     * @inheritdoc
     */
    public function contains(string $key): bool
    {
        return $this->cacheProvider->contains($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function delete(string $key): bool
    {
        return $this->cacheProvider->delete($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function getString(string $key): ?string
    {
        return $this->cacheProvider->getString($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function getObject(string $key): ?object
    {
        return $this->cacheProvider->getObject($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function getArray(string $key): ?array
    {
        return $this->cacheProvider->getArray($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function getInt(string $key): ?int
    {
        return $this->cacheProvider->getInt($this->getNSKey($key));
    }

    /**
     * @inheritdoc
     */
    public function getFloat(string $key): ?float
    {
        return $this->cacheProvider->getFloat($this->getNSKey($key));
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->cacheProvider->get($this->getNSKey($key), $default);
    }

    /**
     * @inheritdoc
     */
    public function setString(string $key, string $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->setString($this->getNSKey($key), $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function setObject(string $key, object $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->setObject($this->getNSKey($key), $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function setArray(string $key, array $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->setArray($this->getNSKey($key), $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function setInt(string $key, int $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->setInt($this->getNSKey($key), $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function setFloat(string $key, float $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->setFloat($this->getNSKey($key), $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        return $this->cacheProvider->set($this->getNSKey($key), $value, $ttl);
    }
}