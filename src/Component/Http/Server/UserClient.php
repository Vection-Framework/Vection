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

namespace Vection\Component\Http\Server;

/**
 * Class UserClient
 *
 * @package Vection\Component\Http\Server
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class UserClient
{
    private string $ip;
    private string $host;
    private int $port;
    private string $userAgent;

    /**
     * Client constructor.
     *
     * @param string $ip
     * @param string $requestedHost
     * @param int    $requestedPort
     * @param string $userAgent
     */
    public function __construct(string $ip, string $requestedHost, int $requestedPort, string $userAgent)
    {
        $this->ip        = $ip;
        $this->host      = $requestedHost;
        $this->port      = $requestedPort;
        $this->userAgent = $userAgent;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getRequestedPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getRequestedHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

}
