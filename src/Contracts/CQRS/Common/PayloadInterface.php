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

namespace Vection\Contracts\CQRS\Common;

/**
 * Interface PayloadInterface
 *
 * @package Vection\Contracts\CQRS\Common
 */
interface PayloadInterface
{
    /**
     * Returns the value of the given key or null
     * if the key does not exists.
     *
     * @param int|string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Sets a value by the given key.
     *
     * @param int|string $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * Returns the value by given key and removes
     * it from this payload. Returns null if the key does
     * not exists.
     *
     * @param int|string $key
     * @return mixed
     */
    public function pop($key);

    /**
     * Returns the given key as a new instance of
     * PayloadInterface that contains the content of the
     * selected key. Returns null if the key does not exists.
     *
     * @param int|string $key
     *
     * @return null|PayloadInterface
     */
    public function getPayload($key): ? PayloadInterface;

    /**
     * Returns the payload data as array.
     *
     * @return array
     */
    public function toArray(): array;
}