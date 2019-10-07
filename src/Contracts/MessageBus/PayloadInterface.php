<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
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
    public function get(string $key): ? string;

    /**
     * Sets a value by the given key.
     *
     * @param string $key
     * @param int|string $value
     */
    public function set(string $key, $value): void;

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

    /**
     * Returns a fingerprint from containing data.
     *
     * @return string
     */
    public function getFingerprint(): string;
}
