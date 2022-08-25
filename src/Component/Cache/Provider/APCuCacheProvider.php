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

namespace Vection\Component\Cache\Provider;

use APCUIterator;
use RuntimeException;
use Vection\Contracts\Cache\CacheProviderInterface;
use function apcu_clear_cache;
use function apcu_delete;
use function apcu_exists;
use function apcu_fetch;
use function apcu_store;

/**
 * Class APCuCacheProvider
 *
 * @package Vection\Component\Cache\Provider
 */
class APCuCacheProvider implements CacheProviderInterface
{
    /**
     * APCuCacheProvider constructor.
     */
    public function __construct()
    {
        if ( ! extension_loaded('apcu') ) {
            throw new RuntimeException('APCuCacheProvider requires the apcu extension.');
        }
    }

    /**
     * @inheritDoc
     */
    public function contains(string $key): bool
    {
        return apcu_exists($key);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        if ( $this->contains($key) ) {
            return apcu_delete($key);
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getString(string $key): ?string
    {
        return $this->contains($key) ? (string) $this->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getObject(string $key): ?object
    {
        return $this->contains($key) ? (object) $this->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getArray(string $key): ?array
    {
        return $this->contains($key) ? (array) $this->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getInt(string $key): ?int
    {
        return $this->contains($key) ? (int) $this->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function getFloat(string $key): ?float
    {
        return $this->contains($key) ? (float) $this->get($key) : null;
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->contains($key) ? apcu_fetch($key) : $default;
    }

    /**
     * @inheritDoc
     */
    public function setString(string $key, string $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setObject(string $key, object $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setArray(string $key, array $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setInt(string $key, int $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function setFloat(string $key, float $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, mixed $value, int $ttl = 0): bool
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function clear(string $namespace = ''): bool
    {
        if ( ! $namespace ) {
            return apcu_clear_cache();
        }

        $it = new APCUIterator('user');
        foreach ( $it as $item ) {
            if (str_starts_with($item['key'], $namespace)) {
                $this->delete($item['key']);
            }
        }

        return true;
    }

}
