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

namespace Vection\Component\Messenger;

use Vection\Contracts\Messenger\MessageHeadersInterface;

/**
 * Class MessageHeaders
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageHeaders implements MessageHeadersInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var integer
     */
    protected $timestamp;

    /**
     * @var array
     */
    protected $userData;

    /**
     * MessageHeaders constructor.
     *
     * @param string $id
     * @param int    $timestamp
     * @param array  $userData
     */
    public function __construct(string $id, int $timestamp, array $userData = [])
    {
        $this->id        = $id;
        $this->timestamp = $timestamp;
        $this->userData  = $userData;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return (int) $this->timestamp;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function get(string $name): ?string
    {
        return ($this->userData[$name] ?? null);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->userData);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array       = $this->userData;
        $array['id'] = $this->id;
        $array['timestamp'] = $this->timestamp;
        return $array;
    }
}
