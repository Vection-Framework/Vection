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

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vection\Component\Http\Headers;
use Vection\Component\Http\Stream;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Responder
 *
 * @package Vection\Component\Http\Server
 */
class Responder implements ResponderInterface
{
    /**
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * @param ResponseInterface      $response
     * @param ServerRequestInterface $request
     */
    public function send(ResponseInterface $response, ServerRequestInterface $request): void
    {
        $status = $response->getStatusCode();

        if( $request->getProtocolVersion() !== $response->getProtocolVersion() ){
            $response = $response->withProtocolVersion($request->getProtocolVersion());
        }

        if( $request->getMethod() === 'HEAD' || $status < 200 || in_array($status, [204, 304]) ){
            $response->withBody(new Stream());
        }

        if( ! headers_sent() ){
            foreach( $this->getHeaders($response, $request)->toArray() as $name => $values ){
                header($name.':'.implode(', ', $values), true, $status);
            }

            header("HTTP/{$response->getProtocolVersion()} {$status} {$response->getReasonPhrase()}");
        }

        echo $response->getBody()->getContents();
    }

    /**
     * @param ResponseInterface      $response
     * @param ServerRequestInterface $request
     *
     * @return Headers
     */
    protected function getHeaders(ResponseInterface $response, ServerRequestInterface $request): Headers
    {
        $headers = new Headers($response->getHeaders());

        # Content-Type
        if( ! $headers->has('Content-Type') ){
            $headers->set('Content-Type', 'text/html; charset='.$this->charset);
        }else{
            $contentType = $headers->getLine('Content-Type');

            if( strpos($contentType, 'charset') === false ){
                $types = $headers->get('Content-Type');
                $types[count($types)-1] .= '; charset='.$this->charset;
                $headers->set('Content-Type', $types);
            }
        }

        if( ! $headers->has('Content-Length') ){
            $headers->set('Content-Length', $response->getBody()->getSize());
        }

        # Status code
        if( $response->getStatusCode() < 200 || in_array($response->getStatusCode(), [204, 304])){
            $headers->remove('Content-Type');
            $headers->remove('Content-Length');
        }

        # Caching
        if( $response->getProtocolVersion() === '1.0' && $headers->hasValue('Cache-Control', 'no-cache') ){
            $headers->set('pragma', 'no-cache');
            $headers->set('expires', "-1");
        }

        # Other RFC adaption
        if( $response->getStatusCode() === 304 ){
            $remove = [
                'Allow', 'Content-Encoding', 'Content-Language', 'Content-Length',
                'Content-MD5', 'Content-Type', 'Last-Modified'
            ];

            foreach( $remove as $headerName ){
                $headers->remove($headerName);
            }
        }

        if( $headers->has('Transfer-Encoding') ){
            $headers->remove('Content-Length');
        }


        return $headers;
    }
}