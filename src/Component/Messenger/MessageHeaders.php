<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
    public const MESSAGE_ID     = 'MESSAGE_ID';
    public const REQUEST_ID     = 'REQUEST_ID';
    public const CORRELATION_ID = 'CORRELATION_ID';
    public const CAUSATION_ID   = 'CAUSATION_ID';
    public const USER_ID        = 'USER_ID';
    public const TENANT_ID      = 'TENANT_ID';
    public const APP_ID         = 'APP_ID';
    public const CONTEXT        = 'CONTEXT';
    public const TIMESTAMP      = 'TIMESTAMP';
    public const DELIVERY_TIMESTAMP    = 'DELIVERY_TIMESTAMP';
    public const REDELIVERED_TIMESTAMP = 'REDELIVERED_TIMESTAMP';
    public const RECEIVED_TIMESTAMP    = 'RECEIVED_TIMESTAMP';
    public const TERMINATED_MIDDLEWARE = 'TERMINATED_MIDDLEWARE';
    public const HANDLED_TIMESTAMP     = 'HANDLED_TIMESTAMP';
    public const MESSAGE_TYPE          = 'MESSAGE_TYPE';
    public const MESSAGE_TAG           = 'MESSAGE_TAG';

    protected array $map;

    /**
     * MessageHeaders constructor.
     *
     * @param array  $map
     */
    public function __construct(array $map = [])
    {
        $this->map = $map;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function get(string $name): ?string
    {
        return ($this->map[$name] ?? null);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->map);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->map;
    }
}
