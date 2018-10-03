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

namespace Vection\Component\Cache\Provider;

use Redis;
use Vection\Contracts\Cache\CacheProviderInterface;

/**
 * Class RedisCacheProvider
 *
 * @package Vection\Component\Cache\Provider
 */
class RedisCacheProvider implements CacheProviderInterface
{
    /** @var Redis */
    protected $redis;

    /**
     * RedisCacheProvider constructor.
     *
     * @param Redis $redis
     *
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Checks whether the key exists in the cache.
     * Returns true id the cache item is containing
     * by the cache, otherwise false.
     *
     * @param string $key
     *
     * @return bool
     */
    public function contains(string $key): bool
    {
        return $this->redis->exists($key);
    }

    /**
     * Deletes a value by given key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool
    {
        return (bool)$this->redis->delete($key);
    }

    /**
     * Returns the string value by given key.
     *
     * @param string $key
     *
     * @return null|string
     */
    public function getString(string $key): ?string
    {
        return $this->contains($key) ? (string)$this->redis->get($key) : null;
    }

    /**
     * Returns the object value by given key.
     *
     * @param string $key
     *
     * @return null|object
     */
    public function getObject(string $key): ?object
    {
        return $this->contains($key) ? (object)$this->redis->get($key) : null;
    }

    /**
     * Returns the array value by given key.
     *
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array
    {
        return $this->contains($key) ? (array)$this->redis->get($key) : null;
    }

    /**
     * Returns the int value by given key.
     *
     * @param string $key
     *
     * @return int|null
     */
    public function getInt(string $key): ?int
    {
        return $this->contains($key) ? (int)$this->redis->get($key) : null;
    }

    /**
     * Returns the float value by given key.
     *
     * @param string $key
     *
     * @return float|null
     */
    public function getFloat(string $key): ?float
    {
        return $this->contains($key) ? (float)$this->redis->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->contains($key) ? $this->redis->get($key) : $default;
    }

    /**
     * Sets the given string value into the cache.
     *
     * @param string $key
     * @param string $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function setString(string $key, string $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, $value, $ttl === 0 ? null : $ttl);
    }

    /**
     * Sets the given object value into the cache.
     *
     * @param string $key
     * @param object $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function setObject(string $key, object $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, $value, $ttl === 0 ? null : $ttl);
    }

    /**
     * Sets the given array value into the cache.
     *
     * @param string $key
     * @param array  $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function setArray(string $key, array $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, \json_encode($value), $ttl === 0 ? null : $ttl);
    }

    /**
     * Sets the given int value into the cache.
     *
     * @param string $key
     * @param int    $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function setInt(string $key, int $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, (string)$value, $ttl === 0 ? null : $ttl);
    }

    /**
     * Sets the given float value into the cache.
     *
     * @param string $key
     * @param float  $value
     * @param int    $ttl
     *
     * @return bool
     */
    public function setFloat(string $key, float $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, (string)$value, $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, $value, $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        # TODO: Redis Cache Provider - Implement clear method with namespace support
        return true;
    }

}