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
     * @inheritDoc
     */
    public function contains(string $key): bool
    {
        return $this->redis->exists($key);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return (bool)$this->redis->delete($key);
    }

    /**
     * @inheritDoc
     */
    public function getString(string $key): ?string
    {
        return $this->contains($key) ? (string) $this->redis->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getObject(string $key): ?object
    {
        return $this->contains($key) ? \unserialize($this->redis->get($key)) : null;
    }

    /**
     * @inheritDoc
     */
    public function getArray(string $key): ?array
    {
        return $this->contains($key) ? \json_decode($this->redis->get($key), true) : null;
    }

    /**
     * @inheritDoc
     */
    public function getInt(string $key): ?int
    {
        return $this->contains($key) ? (int) $this->redis->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getFloat(string $key): ?float
    {
        return $this->contains($key) ? (float) $this->redis->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->contains($key) ? $this->redis->get($key) : $default;
    }

    /**
     * @inheritDoc
     */
    public function setString(string $key, string $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, $value, $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setObject(string $key, object $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, \serialize($value), $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setArray(string $key, array $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, \json_encode($value), $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setInt(string $key, int $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, (string) $value, $ttl === 0 ? null : $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setFloat(string $key, float $value, int $ttl = 0): bool
    {
        return $this->redis->set($key, (string) $value, $ttl === 0 ? null : $ttl);
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
        if( ! $namespace ){
            return $this->redis->flushAll();
        }

        $iterator = null;
        $infinityLoopProtection = 0;
        $this->redis->setOption(\Redis::OPT_SCAN, \Redis::SCAN_RETRY);

        while( ($keys = $this->redis->scan($iterator, addslashes($namespace).'*', 10000)) || $infinityLoopProtection > 1000 ){
            $this->redis->delete($keys);
            $infinityLoopProtection++;
        }

        return true;
    }

}