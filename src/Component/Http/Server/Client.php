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

declare(strict_types=1);

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http\Server;

/**
 * Class Client
 *
 * @package Vection\Component\Http\Server
 */
class Client
{

    /** @var string */
    private $ip;

    /** @var string */
    private $host;

    /** @var integer */
    private $port;

    /**
     * Client constructor.
     *
     * @param string $ip
     * @param string $requestedHost
     * @param int    $requestedPort
     */
    public function __construct(string $ip, string $requestedHost, int $requestedPort)
    {
        $this->ip   = $ip;
        $this->host = $requestedHost;
        $this->port = $requestedPort;
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
}
