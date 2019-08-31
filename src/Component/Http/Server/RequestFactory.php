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

use Vection\Component\Http\Factory\HeadersFactory;
use Vection\Component\Http\Factory\UriFactory;
use Vection\Component\Http\Psr\Stream;

/**
 * Class ServerRequestFactory
 *
 * @package Vection\Component\Http\Server
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
        $requestBody = new Stream('php://input');
        $version = explode('/', $_SERVER['SERVER_PROTOCOL'])[1];

        $request = new Request(
            $_SERVER['REQUEST_METHOD'], $uri, $headers, $requestBody, $version
        );


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