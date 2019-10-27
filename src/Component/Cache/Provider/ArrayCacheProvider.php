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

declare(strict_types=1);

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Cache\Provider;

use Vection\Contracts\Cache\CacheProviderInterface;

/**
 * Class ArrayCacheProvider
 *
 * @package Vection\Component\Cache\Provider
 */
class ArrayCacheProvider implements CacheProviderInterface
{

    /** @var array */
    protected $cache = [];

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
        return \array_key_exists($key, $this->cache);
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
        if ( $this->contains($key) ) {
            unset($this->cache[$key]);

            return true;
        }

        return false;
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
        return $this->contains($key) ? (string) $this->cache[$key] : null;
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
        return $this->contains($key) ? (object) $this->cache[$key] : null;
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
        return $this->contains($key) ? (array) $this->cache[$key] : null;
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
        return $this->contains($key) ? (int) $this->cache[$key] : null;
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
        return $this->contains($key) ? (float) $this->cache[$key] : null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return $this->contains($key) ? $this->cache[$key] : $default;
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
        $this->cache[$key] = $value;

        return true;
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
        $this->cache[$key] = $value;

        return true;
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
        $this->cache[$key] = $value;

        return true;
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
        $this->cache[$key] = $value;

        return true;
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
        $this->cache[$key] = $value;

        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        $this->cache[$key] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        foreach ( $this->cache as $key => $value ) {
            if ( ! $namespace || strpos($key, $namespace) === 0 ) {
                unset($this->cache[$key]);
            }
        }

        return true;
    }

}
