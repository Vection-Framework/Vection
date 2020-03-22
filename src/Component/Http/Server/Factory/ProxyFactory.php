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

namespace Vection\Component\Http\Server\Factory;

use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Server\Proxy;

/**
 * Class ProxyFactory
 *
 * @package Vection\Component\Http\Server\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ProxyFactory
{
    /**
     * @param Environment $environment
     *
     * @return Proxy
     */
    public static function create(Environment $environment): Proxy
    {
        $for  = [];
        $host = $proto = '';
        $port = '';

        if ( $environment->has(Proxy::HEADER_FORWARDED) ) {
            $directives = self::parseForwardedRFC7239($environment->get(Proxy::HEADER_FORWARDED));

            $for   = ($directives['for'] ?? []);
            $host  = ($directives['host'] ?? '');
            $proto = ($directives['proto'] ?? '');
        }

        if ( ! $for && $environment->has(Proxy::HEADER_X_FORWARDED_FOR) ) {
            # Set the originating IP address of a client
            $for = self::parseForwardedFor($environment->get(Proxy::HEADER_X_FORWARDED_FOR));
        }

        if ( ! $for && $environment->has(Proxy::HEADER_X_PROXYUSER_IP) ) {
            # Used for some Google services
            $for = self::parseForwardedFor($environment->get(Proxy::HEADER_X_PROXYUSER_IP));
        }

        if ( ! $host && $environment->has(Proxy::HEADER_X_FORWARDED_HOST) ) {
            $host = $environment->get(Proxy::HEADER_X_FORWARDED_HOST);
        }

        if ( ! $proto && $environment->has(Proxy::HEADER_X_FORWARDED_PROTO) ) {
            $proto = $environment->get(Proxy::HEADER_X_FORWARDED_PROTO);
        }

        if ( $environment->has(Proxy::HEADER_X_FORWARDED_PORT) ) {
            $port = $environment->get(Proxy::HEADER_X_FORWARDED_PORT);
        }

        return new Proxy($for, $host, $port, $proto);
    }

    /**
     * @param string $forwarded
     *
     * @return array
     */
    protected static function parseForwardedRFC7239(string $forwarded): array
    {
        $directives = [];

        foreach (explode(';', $forwarded) as $directive) {
            [$name, $value] = explode('=', $directive);
            $name           = strtolower($name);

            if ( $value[0] === '"' ) {
                # remove quotes for e.g. "[2001:db8:cafe::17]:4711"
                $value = substr($value, 1, -1);
            }

            if ( $name === 'for' ) {
                $value = self::parseForwardedFor($value);
            }

            # multiple values can be appended using a comma, so check if this directive already exists
            # to append to them. E.g. Forwarded: for=192.0.2.43, for=198.51.100.17
            if ( ! isset($directives[$name]) ) {
                $directives[$name] = $value;
            } else if ( is_array($directives[$name]) ) {
                $directives[$name][] = $value;
            } else {
                $directives[$name] .= ', ' . $value;
            }
        }

        return $directives;
    }

    /**
     * @param string $forwardedFor
     *
     * @return array
     */
    protected static function parseForwardedFor(string $forwardedFor): array
    {
        return array_map('trim', explode(',', $forwardedFor));
    }
}
