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

namespace Vection\Component\Http\Server\Decorator\Factory;

use InvalidArgumentException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Server\Environment;

/**
 * Class UriFactoryDecorator
 *
 * @package Vection\Component\Http\Server\Decorator\Factory
 */
class UriFactoryDecorator implements UriFactoryInterface
{
    /**
     * @var UriFactoryInterface
     */
    protected $uriFactory;

    /**
     * UriFactory constructor.
     *
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct(UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;
    }

    /**
     * Create a new URI.
     *
     * @param string $uri
     *
     * @return UriInterface
     *
     * @throws InvalidArgumentException If the given URI cannot be parsed.
     */
    public function createUri(string $uri = ''): UriInterface
    {
        return $this->uriFactory->createUri($uri);
    }

    /**
     * @param Environment|null $environment
     *
     * @return UriInterface
     */
    public function createFromGlobals(Environment $environment = null): UriInterface
    {
        if( ! $environment ){
            $environment = new Environment();
        }

        $components = parse_url($environment->getRequestUri());

        if( ! isset($components['scheme']) ){

            [$protocol] = explode('/', $environment->getServerProtocol());

            if( $protocol === 'HTTP' ){
                $scheme = $environment->get('REQUEST_SCHEME');
                $https = $environment->getHttps();
                $protocol .= ($scheme && $scheme === 'https') || ($https && $https !== 'off') ? 's' : '';
            }

            $components['scheme'] = strtolower($protocol);
        }

        $host = $environment->getServerName() ?? $environment->getServerAddr();
        if( $host && ! isset($components['host']) ){
            $components['host'] = $host;
        }

        $serverPort = $environment->getServerPort();
        if( $serverPort && ! isset($components['port']) ){
            $components['port'] = $serverPort;
        }

        if( ! isset($components['path']) ){
            # In case an uri string parameter is given without path but request contains one
            $reqComps = parse_url($environment->getRequestUri());
            if( isset($reqComps['path']) ){
                $components['path'] = $reqComps['path'];
            }
        }

        $queryString = $environment->getQueryString();
        if( $queryString && ! isset($components['query']) ){
            $components['query'] = $queryString;
        }

        $uri = '';

        if( isset($components['scheme']) ){
            $uri .= $components['scheme'] .'://';
        }

        if( isset($components['host']) ){
            $uri .= $components['host'];
        }
        if( isset($components['port']) ){
            $uri .= ':' . $components['port'];
        }

        if( isset($components['path']) ){
            $uri .= '/' . trim($components['path'], '/');
        }

        if( isset($components['query']) ){
            $uri .= '?' . $components['query'];
        }

        return $this->createUri($uri);
    }

}