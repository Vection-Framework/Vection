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

declare(strict_types=1);

namespace Vection\Component\Http\Server\Factory;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Port;
use Vection\Component\Http\Psr\Factory\UriFactory as PsrUriFactory;
use Vection\Component\Http\Server\Environment;

/**
 * Class UriFactory
 *
 * @package Vection\Component\Http\Server\Factory
 */
class UriFactory extends PsrUriFactory
{
    /** @var Environment */
    protected $environment;

    /**
     * UriFactory constructor.
     *
     * @param Environment $environment
     */
    public function __construct(Environment $environment = null)
    {
        $this->environment = $environment ?: new Environment();
    }

    /**
     * @inheritDoc
     */
    public function createUri(string $uriString = ''): UriInterface
    {
        $components = parse_url($uriString ?: $this->environment->getRequestUri());

        if( ! $components ){
            throw new InvalidArgumentException("Invalid URI '$uriString'");
        }

        if( ! isset($components[PHP_URL_SCHEME]) ){

            [$protocol] = explode('/', $this->environment->getServerProtocol());

            if( $protocol === 'HTTP' ){
                $scheme = $this->environment->get('REQUEST_SCHEME');
                $https = $this->environment->getHttps();
                $protocol .= ($scheme && $scheme === 'https') || ($https && $https !== 'off') ? 's' : '';
            }

            $components[PHP_URL_SCHEME] = strtolower($protocol);
        }

        $host = $this->environment->getServerName() ?? $this->environment->getServerAddr();
        if( $host && ! isset($components[PHP_URL_HOST]) ){
            $components[PHP_URL_HOST] = $host;
        }

        $serverPort = $this->environment->getServerPort();
        if( $serverPort && ! isset($components[PHP_URL_PORT]) ){
            $components[PHP_URL_PORT] = $serverPort;
        }

        if( ! isset($components[PHP_URL_PATH]) ){
            # In case an uri string parameter is given without path but request contains one
            $reqComps = parse_url($this->environment->getRequestUri());
            if( isset($reqComps[PHP_URL_PATH]) ){
                $components[PHP_URL_PORT] = $reqComps[PHP_URL_PATH];
            }
        }

        $queryString = $this->environment->getQueryString();
        if( $queryString && ! isset($components[PHP_URL_QUERY]) ){
            $components[PHP_URL_QUERY] = $queryString;
        }

        $uri = $this->createFromUrlComponents($components);

        if( ! $uri->getPort() ){
            $port = Port::getDefaultPort($uri->getScheme());
            if( $port ){
                $uri = $uri->withPort($port);
            }
        }

        return $uri;
    }



}