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

declare(strict_types = 1);

namespace Vection\Contracts\Messenger;

/**
 * Interface MessageHeadersInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageHeadersInterface
{
    /**
     * Returns a global unique identifier for this message.
     * Each created message has its own global unique id.
     *
     * @return string
     */
    public function getId(): string;

    /**
     * Returns the timestamp the message was created at.
     *
     * @return int
     */
    public function getTimestamp(): int;

    /**
     * Returns a value from the user data of this headers object.
     *
     * @param string $name
     *
     * @return string|null
     */
    public function get(string $name): ?string;

    /**
     * Sets a value to the user data of this headers object.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * Returns the headers as array.
     *
     * @return array
     */
    public function toArray(): array;
}
