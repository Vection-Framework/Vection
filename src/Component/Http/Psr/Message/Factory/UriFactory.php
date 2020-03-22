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

namespace Vection\Component\Http\Psr\Message\Factory;

use InvalidArgumentException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Port;
use Vection\Component\Http\Psr\Message\Uri;

/**
 * Class UriFactory
 *
 * @package Vection\Component\Http\Psr\Message\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class UriFactory implements UriFactoryInterface
{

    /**
     * Create a new URI.
     *
     * @param string $uriString
     *
     * @return UriInterface
     *
     * @throws InvalidArgumentException If the given URI cannot be parsed.
     */
    public function createUri(string $uriString = ''): UriInterface
    {
        if ( ! $uriString ) {
            return new Uri();
        }

        if ( ! $components = parse_url($uriString) ) {
            throw new InvalidArgumentException("Invalid URI '$uriString'");
        }

        $uri = $this->createFromUrlComponents($components);

        if ( ! $uri->getPort() ) {
            $port = Port::getDefaultPort($uri->getScheme());
            if ( $port ) {
                $uri = $uri->withPort($port);
            }
        }

        return $uri;
    }

    /**
     * @param array $urlComponents
     *
     * @return UriInterface
     */
    protected function createFromUrlComponents(array $urlComponents): UriInterface
    {
        $uri = new Uri();

        if ( isset($urlComponents['scheme']) ) {
            $uri = $uri->withScheme($urlComponents['scheme']);
        }

        if ( isset($urlComponents['user']) ) {
            $uri = $uri->withUserInfo($urlComponents['user'], ($urlComponents['pass'] ?? null));
        }

        if ( isset($urlComponents['host']) ) {
            $uri = $uri->withHost($urlComponents['host']);
        }

        if ( isset($urlComponents['port']) ) {
            $uri = $uri->withPort((int) $urlComponents['port']);
        }

        if ( isset($urlComponents['path']) ) {
            $uri = $uri->withPath($urlComponents['path']);
        }

        if ( isset($urlComponents['query']) ) {
            $uri = $uri->withQuery($urlComponents['query']);
        }

        if ( isset($urlComponents['fragment']) ) {
            $uri = $uri->withFragment($urlComponents['fragment']);
        }

        return $uri;
    }
}
