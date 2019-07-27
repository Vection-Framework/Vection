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

namespace Vection\Component\Cache\Provider;

use Vection\Contracts\Cache\CacheProviderInterface;

/**
 * Class VoidCacheProvider
 *
 * @package Vection\Component\Cache\Provider
 */
class VoidCacheProvider implements CacheProviderInterface
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
    public function contains(string $key): bool
    {
        return false;
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
        return true;
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
        return null;
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
        return null;
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
        return null;
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
        return null;
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
        return null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        return null;
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
        return true;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value, int $ttl = 0): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        return true;
    }

}