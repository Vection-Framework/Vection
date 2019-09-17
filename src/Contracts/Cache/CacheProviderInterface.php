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
 * Interface CacheProviderInterface
 *
 * @package Vection\Contracts\Cache
 */
interface CacheProviderInterface
{

    /**
     * Checks whether the key exists in the cache.
     * Returns true id the cache item is containing
     * by the cache, otherwise false.
     *
     * @param string $key
     *
     * @return bool
     */
    public function contains(string $key): bool;

    /**
     * Deletes a value by given key.
     *
     * @param string $key
     *
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Returns the string value by given key.
     *
     * @param string $key
     *
     * @return null|string
     */
    public function getString(string $key): ?string;

    /**
     * Returns the object value by given key.
     *
     * @param string $key
     *
     * @return null|object
     */
    public function getObject(string $key): ?object;

    /**
     * Returns the array value by given key.
     *
     * @param string $key
     *
     * @return array|null
     */
    public function getArray(string $key): ?array;

    /**
     * Returns the int value by given key.
     *
     * @param string $key
     *
     * @return int|null
     */
    public function getInt(string $key): ?int;

    /**
     * Returns the float value by given key.
     *
     * @param string $key
     *
     * @return float|null
     */
    public function getFloat(string $key): ?float;

    /**
     * Returns the value by given key.
     *
     * @param string $key The unique key of this item in the cache.
     * @param mixed $default Default value to return if the key does not exist.
     *
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Sets the given string value into the cache.
     *
     * @param string $key
     * @param string $value
     * @param int $ttl
     *
     * @return bool
     */
    public function setString(string $key, string $value, int $ttl = 0): bool;

    /**
     * Sets the given object value into the cache.
     *
     * @param string $key
     * @param object $value
     * @param int $ttl
     *
     * @return bool
     */
    public function setObject(string $key, object $value, int $ttl = 0): bool;

    /**
     * Sets the given array value into the cache.
     *
     * @param string $key
     * @param array $value
     * @param int $ttl
     *
     * @return bool
     */
    public function setArray(string $key, array $value, int $ttl = 0): bool;

    /**
     * Sets the given int value into the cache.
     *
     * @param string $key
     * @param int $value
     * @param int $ttl
     *
     * @return bool
     */
    public function setInt(string $key, int $value, int $ttl = 0): bool;

    /**
     * Sets the given float value into the cache.
     *
     * @param string $key
     * @param float $value
     * @param int $ttl
     *
     * @return bool
     */
    public function setFloat(string $key, float $value, int $ttl = 0): bool;

    /**
     * Sets the given value into the cache.
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl
     *
     * @return bool
     */
    public function set(string $key, $value, int $ttl = 0): bool;

    /**
     * Clears all entries of this cache. If namespace is given then
     * only those entries will be cleared which are saved within
     * the given namespace.
     *
     * @param string $namespace
     *
     * @return bool
     */
    public function clear(string $namespace = ''): bool;
}