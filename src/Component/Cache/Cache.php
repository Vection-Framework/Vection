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
     * @var string
     */
    protected $namespace;

    /**
     * @var CacheProviderInterface
     */
    protected $cacheProvider;

    /**
     * @var CacheInterface[]
     */
    protected $pools;

    /**
     * Cache constructor.
     *
     * @param CacheProviderInterface $cacheProvider
     * @param null|string $namespace
     */
    public function __construct(CacheProviderInterface $cacheProvider, string $namespace = '')
    {
        $this->cacheProvider = $cacheProvider;
        $this->namespace = $namespace ?: 'Vection-Cache';
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
            $pool = new Cache($this->cacheProvider, $this->namespace . ':' . $namespace);
            $this->pools[$namespace] = $pool;
        }

        return $this->pools[$namespace];
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        $this->cacheProvider->clear($this->getNSKey($namespace));

        return true;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function getNSKey(string $key): string
    {
        return $this->namespace . ( $key ? ':' . $key : '' );
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
    public function get(string $key)
    {
        return $this->cacheProvider->get($this->getNSKey($key));
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