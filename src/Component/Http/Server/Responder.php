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

namespace Vection\Component\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vection\Component\Http\Headers;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Responder
 *
 * @package Vection\Component\Http\Server
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Responder implements ResponderInterface
{

    /**
     * @var string
     */
    protected $charset = 'utf-8';

    /**
     * @var array
     */
    protected $headerReplacements = [];

    /**
     * @param string $charset
     */
    public function setDefaultCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * @param array $headers
     */
    public function setHeaderReplacements(array $headers): void
    {
        $this->headerReplacements = $headers;
    }

    /**
     * @param ResponseInterface      $response
     * @param ServerRequestInterface $request
     */
    public function send(ResponseInterface $response, ServerRequestInterface $request): void
    {
        $status = $response->getStatusCode();

        # Prepare some basic response properties
        if ( $request->getProtocolVersion() !== $response->getProtocolVersion() ) {
            $response = $response->withProtocolVersion($request->getProtocolVersion());
        }

        # Prepare and send response header
        if ( ! headers_sent() ) {
            foreach ( $this->getFixedHeaders($response)->toArray() as $name => $values ) {
                header($name.': '.implode(', ', $values), true, $status);
            }

            header("HTTP/{$response->getProtocolVersion()} {$status} {$response->getReasonPhrase()}");
        }

        if ( $status >= 200 && $request->getMethod() !== 'HEAD' && ! in_array($status, [204, 304], true) ) {
            # Send response body as string
            echo $response->getBody()->getContents();
        }
    }

    /**
     * @param ResponseInterface      $response
     *
     * @return Headers
     */
    protected function getFixedHeaders(ResponseInterface $response): Headers
    {
        $headers = new Headers($response->getHeaders());

        # Content-Type
        if ( ! $headers->has('Content-Type') ) {
            $headers->set('Content-Type', 'text/html; charset='.$this->charset);
        } else {
            $contentType = $headers->getLine('Content-Type');

            if ( strpos($contentType, 'charset') === false ) {
                $types = $headers->get('Content-Type');
                $types[(count($types) - 1)] .= '; charset='.$this->charset;
                $headers->set('Content-Type', $types);
            }
        }

        if ( ! $headers->has('Content-Length') ) {
            $headers->set('Content-Length', $response->getBody()->getSize());
        }

        # Status code
        if (! $headers->has('Status')) {
            $headers->set('Status', $response->getStatusCode().' '.$response->getReasonPhrase());
        }

        if ( $response->getStatusCode() < 200 || in_array($response->getStatusCode(), [204, 304], true) ) {
            $headers->remove('Content-Type');
            $headers->remove('Content-Length');
        }

        # Caching
        if ( $response->getProtocolVersion() === '1.0' && $headers->hasValue('Cache-Control', 'no-cache') ) {
            $headers->set('pragma', 'no-cache');
            $headers->set('expires', '-1');
        }

        # Other RFC adaption
        if ( $response->getStatusCode() === 304 ) {
            $remove = [
                'Allow', 'Content-Encoding', 'Content-Language', 'Content-Length',
                'Content-MD5', 'Content-Type', 'Last-Modified'
            ];

            foreach ( $remove as $headerName ) {
                $headers->remove($headerName);
            }
        }

        if ( $headers->has('Transfer-Encoding') ) {
            $headers->remove('Content-Length');
        }

        foreach ( $this->headerReplacements as $name => $value ) {
            $headers->set($name, $value);
        }

        return $headers;
    }
}
