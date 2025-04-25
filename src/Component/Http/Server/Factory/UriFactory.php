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

use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Psr\Message\Factory\UriFactory as PsrUriFactory;
use Vection\Component\Http\Server\Environment;

/**
 * Class UriFactory
 *
 * @package Vection\Component\Http\Server\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class UriFactory extends PsrUriFactory
{
    /**
     * @param Environment|null $environment
     *
     * @return UriInterface
     */
    public function createUriFromEnvironment(Environment|null $environment = null): UriInterface
    {
        if ( ! $environment ) {
            $environment = new Environment();
        }

        $components = parse_url($environment->getRequestUri());

        if ( ! isset($components['scheme']) ) {

            [$protocol] = explode('/', $environment->getServerProtocol());

            if ( $protocol === 'HTTP' ) {
                $scheme    = $environment->get('REQUEST_SCHEME');
                $https     = $environment->getHttps();
                $protocol .= ($scheme === 'https') || ($https && $https !== 'off') ? 's' : '';
            }

            $components['scheme'] = strtolower($protocol);
        }

        $host = ($environment->getServerName() ?? $environment->getServerAddr());
        if ( $host && ! isset($components['host']) ) {
            $components['host'] = $host;
        }

        $serverPort = $environment->getServerPort();
        if ( $serverPort && ! isset($components['port']) ) {
            $components['port'] = $serverPort;
        }

        if ( ! isset($components['path']) ) {
            # In case an uri string parameter is given without path but request contains one
            $reqComps = parse_url($environment->getRequestUri());
            if ( isset($reqComps['path']) ) {
                $components['path'] = $reqComps['path'];
            }
        }

        $queryString = $environment->getQueryString();
        if ( $queryString && ! isset($components['query']) ) {
            $components['query'] = $queryString;
        }

        $uri = '';

        if ( isset($components['scheme']) ) {
            $uri .= $components['scheme'] .'://';
        }

        if ( isset($components['host']) ) {
            $uri .= $components['host'];
        }
        if ( isset($components['port']) ) {
            $uri .= ':' . $components['port'];
        }

        if ( isset($components['path']) ) {
            $uri .= '/' . trim($components['path'], '/');
        }

        if ( isset($components['query']) ) {
            $uri .= '?' . $components['query'];
        }

        return $this->createUri($uri);
    }

}
