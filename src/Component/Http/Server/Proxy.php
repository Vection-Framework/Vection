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

use function Vection\Component\Http\Common\count;

/**
 * Class Proxy
 *
 * @package Vection\Component\Http\Server
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Proxy
{
    public const HEADER_FORWARDED         = 'HTTP_FORWARDED';
    public const HEADER_X_FORWARDED_FOR   = 'HTTP_X_FORWARDED_FOR';
    public const HEADER_X_FORWARDED_HOST  = 'HTTP_X_FORWARDED_HOST';
    public const HEADER_X_FORWARDED_PROTO = 'HTTP_X_FORWARDED_PROTO';
    public const HEADER_X_FORWARDED_PORT  = 'HTTP_X_FORWARDED_PORT';
    public const HEADER_X_PROXYUSER_IP    = 'HTTP_X_PROXYUSER_IP';

    protected array $ips;
    protected ?string $originHost;
    protected ?int $originPort;
    protected ?string $originProto;

    /**
     * Proxy constructor.
     *
     * @param array       $ips
     * @param string|null $originHost
     * @param int|null    $originPort
     * @param string|null $originProto
     */
    public function __construct(array $ips, ? string $originHost, ? int $originPort, ? string $originProto)
    {
        $this->ips         = $ips ?: [];
        $this->originHost  = $originHost;
        $this->originPort  = $originPort;
        $this->originProto = $originProto;

        # TODO normalize ip into ip and port
        # TODO if no port given set port from ips
    }

    /**
     * @return array
     */
    public function getIPs(): array
    {
        return $this->ips;
    }

    /**
     *
     * @return string|null
     */
    public function getRecentProxyIP(): ? string
    {
        return count($this->ips) > 1 ? end($this->ips) : null;
    }

    /**
     * @return array
     */
    public function getProxyIPChain(): array
    {
        $ips = $this->ips;
        array_shift($ips);
        return $ips;
    }

    /**
     * @return string
     */
    public function getClientIP(): string
    {
        return reset($this->ips);
    }

    /**
     * @return string
     */
    public function getOriginHost(): ? string
    {
        return $this->originHost;
    }

    /**
     * @return int
     */
    public function getOriginPort(): ? int
    {
        return $this->originPort;
    }

    /**
     * @return string
     */
    public function getOriginProtocol(): ? string
    {
        return $this->originProto;
    }

}
