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

namespace Vection\Contracts\MessageBus;

/**
 * Interface PayloadInterface
 *
 * @package Vection\Contracts\MessageBus
 */
interface PayloadInterface
{
    /**
     * Returns the value of the given key or null
     * if the key does not exists.
     *
     * @param string $key
     * @return null|string
     */
    public function get($key): ? string;

    /**
     * Sets a value by the given key.
     *
     * @param string $key
     * @param int|string $value
     */
    public function set($key, $value): void;

    /**
     * Returns the value by given key and removes
     * it from this payload. Returns null if the key does
     * not exists.
     *
     * @param string $key
     * @return null|string
     */
    public function pop($key): ? string;

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
