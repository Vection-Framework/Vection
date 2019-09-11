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

namespace Vection\Component\Http\Psr\Factory;

use InvalidArgumentException;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Port;
use Vection\Component\Http\Psr\Uri;

/**
 * Class UriFactory
 *
 * @package Vection\Component\Http\Psr\Factory
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
        if( ! $uriString ){
            return new Uri();
        }

        if( ! $components = parse_url($uriString) ){
            throw new InvalidArgumentException("Invalid URI '$uriString'");
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

    /**
     * @param array $urlComponents
     *
     * @return UriInterface
     */
    protected function createFromUrlComponents(array $urlComponents): UriInterface
    {
        $uri = new Uri();

        if( isset($urlComponents[PHP_URL_SCHEME]) ){
            $uri = $uri->withScheme($urlComponents[PHP_URL_SCHEME]);
        }

        if( isset($urlComponents[PHP_URL_USER]) ){
            $uri = $uri->withUserInfo($urlComponents[PHP_URL_USER], $urlComponents[PHP_URL_PASS] ?? null);
        }

        if( isset($urlComponents[PHP_URL_HOST]) ){
            $uri = $uri->withHost($urlComponents[PHP_URL_HOST]);
        }

        if( isset($urlComponents[PHP_URL_PORT]) ){
            $uri = $uri->withPort((int)$urlComponents[PHP_URL_PORT]);
        }

        if( isset($urlComponents[PHP_URL_PATH]) ){
            $uri = $uri->withPath($urlComponents[PHP_URL_PATH]);
        }

        if( isset($urlComponents[PHP_URL_QUERY]) ){
            $uri = $uri->withQuery($urlComponents[PHP_URL_QUERY]);
        }

        if( isset($urlComponents[PHP_URL_FRAGMENT]) ){
            $uri = $uri->withFragment($urlComponents[PHP_URL_FRAGMENT]);
        }

        return $uri;
    }
}