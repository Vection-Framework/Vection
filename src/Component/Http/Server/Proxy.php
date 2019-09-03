<?php declare(strict_types=1);

/**
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
 * Class Proxy
 *
 * @package Vection\Component\Http\Server
 */
class Proxy
{
    const HEADER_FORWARDED = 'HTTP_FORWARDED';
    const HEADER_X_FORWARDED_FOR = 'HTTP_X_FORWARDED_FOR';
    const HEADER_X_FORWARDED_HOST = 'HTTP_X_FORWARDED_HOST';
    const HEADER_X_FORWARDED_PROTO = 'HTTP_X_FORWARDED_PROTO';
    const HEADER_X_FORWARDED_PORT = 'HTTP_X_FORWARDED_PORT';
    const HEADER_X_FORWARDED_AWS_ELB = 'HTTP_X_FORWARDED_AWS_ELB';
    const HEADER_X_PROXYUSER_IP = 'HTTP_X_PROXYUSER_IP';

    /** @var string */
    protected $ips;

    /** @var string */
    protected $originHost;

    /** @var string */
    protected $originPort;

    /** @var string */
    protected $originProto;

    /**
     * Proxy constructor.
     *
     * @param array  $ips
     * @param string $originHost
     * @param int $originPort
     * @param string $originProto
     */
    public function __construct(array $ips, ? string $originHost, ? int $originPort, ? string $originProto)
    {
        $this->ips = $ips;
        $this->originHost = $originHost;
        $this->originPort = $originPort;
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