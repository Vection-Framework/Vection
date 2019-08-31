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

namespace Vection\Component\Http\Factory;

use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Psr\Uri;

/**
 * Class UriFactory
 *
 * @package Vection\Component\Http\Factory
 */
class UriFactory
{
    /**
     * @return UriInterface
     */
    public static function createFromServer(): UriInterface
    {
        list($protocol) = explode('/', $_SERVER['SERVER_PROTOCOL']);

        $uriComps = parse_url($_SERVER['REQUEST_URI']);

        if( ! isset($uriComps[PHP_URL_SCHEME]) ){

            if( $protocol === 'HTTP' ){
                $protocol .= (($_SERVER['REQUEST_SCHEME'] ?? false) && $_SERVER['REQUEST_SCHEME'] === 'https')
                || (($_SERVER['HTTPS'] ?: false) && $_SERVER['HTTPS'] !== 'off') ? 's' : '';
            }

            $uriComps[PHP_URL_SCHEME] = strtolower($protocol);
        }

        if( ! isset($uriComps[PHP_URL_HOST]) ){
            $uriComps[PHP_URL_HOST] = $_SERVER['SERVER_NAME'] ?? $_SERVER['SERVER_ADDR'];
        }

        if( ! isset($uriComps[PHP_URL_PORT]) ){
            $uriComps[PHP_URL_PORT] = $_SERVER['SERVER_PORT'];

            if( ! $uriComps[PHP_URL_PORT] && in_array($protocol, ['http', 'https']) ){
                $uriComps[PHP_URL_PORT] = $protocol === 'https' ? 443 : 80;
            }
        }

        $uri = (new Uri())
            ->withScheme($uriComps[PHP_URL_SCHEME])
            ->withHost($uriComps[PHP_URL_HOST])
            ->withPath($uriComps[PHP_URL_PATH]);

        if( isset($uriComps[PHP_URL_PORT]) ){
            $uri = $uri->withPort($uriComps[PHP_URL_PORT]);
        }

        if( isset($uriComps[PHP_URL_QUERY]) ){
            $uri = $uri->withQuery($uriComps[PHP_URL_QUERY]);
        }

        if( isset($uriComps[PHP_URL_FRAGMENT]) ){
            $uri = $uri->withFragment($uriComps[PHP_URL_FRAGMENT]);
        }

        if( isset($uriComps[PHP_URL_USER]) ){
            $uri = $uri->withUserInfo($uriComps[PHP_URL_USER], $uriComps[PHP_URL_PASS] ?? null);
        }

        return $uri;
    }
}