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

use Vection\Component\Http\Factory\HeadersFactory;
use Vection\Component\Http\Factory\UriFactory;
use Vection\Component\Http\Server\Request;

/**
 * Class RequestFactory
 *
 * @package Vection\Component\Http\Server\Factory
 */
class RequestFactory
{
    /**
     * @return Request
     */
    public static function createFromServer(): Request
    {
        $headers = HeadersFactory::createFromServer();
        $uri = UriFactory::createFromServer();
        $version = explode('/', $_SERVER['SERVER_PROTOCOL'])[1];

        $request = new Request(
            $_SERVER['REQUEST_METHOD'], $uri, $headers, $version, $_SERVER
        );


        return $request;

        #if( ! $authUser && $headers->has('Authorization') ){
        #    $authorization = $headers->getLine('Authorization');

        #    if( stripos($authorization, 'basic ') !== 0 ){
        #        $parts = explode(':', base64_decode(substr($authorization, 6)), 2);
        #        if( count($parts) === 2 ){
        #            list($authUser, $authPw) = $parts;
        #        }
        #    }
        #}
    }
}