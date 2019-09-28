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

namespace Vection\Component\Http\Server\Factory;

use Vection\Component\Http\Server\Client;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Server\Proxy;

/**
 * Class ClientFactory
 *
 * @package Vection\Component\Http\Server\Factory
 */
class ClientFactory
{
    /**
     * @param Environment $environment
     * @param Proxy|null  $proxy
     *
     * @return Client
     */
    public static function create(Environment $environment, ? Proxy $proxy = null): Client
    {
        $clientIp = $environment->getRemoteAddr();
        $requestedHost = $environment->getRemoteAddr();
        $requestedPort = (int) $environment->getRemotePort();

        if( ! $requestedPort ){

            [$protocol] = explode('/', $environment->getServerProtocol());

            if( $protocol === 'HTTP' ){
                $requestedPort = $environment->get('REQUEST_SCHEME') === 'https'
                || ($environment->has('HTTPS') && $environment->getHttps() !== 'off') ? 443 : 80;
            }
        }

        if( $proxy ){
            $clientIp = $proxy->getClientIP() ?: $clientIp;
            $requestedHost = $proxy->getOriginHost() ?: $requestedHost;
            $requestedPort = $proxy->getOriginPort() ?: $requestedPort;
        }

        return new Client($clientIp, $requestedHost, $requestedPort);
    }
}