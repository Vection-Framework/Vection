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

namespace Vection\Component\Http\Psr;

use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Headers;

/**
 * Class Request
 *
 * @package Vection\Component\Http\Psr
 */
class Request extends Message implements RequestInterface
{
    /** @var UriInterface */
    protected $uri;

    /** @var string */
    protected $method;

    /** @var string */
    protected $target;

    /**
     * Request constructor.
     *
     * @param string               $method
     * @param UriInterface         $uri
     * @param Headers|null         $headers
     * @param StreamInterface|null $body
     * @param string               $version
     */
    public function __construct(
        string $method,
        UriInterface $uri,
        Headers $headers = null,
        StreamInterface $body = null,
        string $version = '1.1'
    )
    {
        parent::__construct(
            $headers ?: new Headers(),
            $body ?: new Stream('php://input'),
            $version
        );

        $this->method = $method;
        $this->uri = $uri;
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if( ! $this->target ){
            $path = $this->uri->getPath();

            if( $query = $this->uri->getQuery() ){
                $path .= '?'.$query;
            }

            $this->target = '/'.ltrim($path, '/');
        }

        return $this->target;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     *
     * @param mixed $requestTarget
     *
     * @return Request
     */
    public function withRequestTarget($requestTarget): Request
    {
        $request = clone $this;
        $request->target = $requestTarget;
        return $request;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     *
     * @return static
     * @throws InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method)
    {
        $request = clone $this;
        $request->method = $method;
        return $request;
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     *
     * @param UriInterface $uri          New request URI to use.
     * @param bool         $preserveHost Preserve the original state of the Host header.
     *
     * @return static
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $request = clone $this;
        $request->uri = $uri;

        if( ! $preserveHost || ! $this->hasHeader('host') ){

            if( ! $host = $request->uri->getHost() ){
                return $request;
            }

            if( $port = $request->uri->getPort() ){
                $host .= ':'.$port;
            }

            foreach( $request->headers as $key => $header ){
                if( strtolower($key) === 'host' ){
                    unset($request->headers[$key]);
                    $request->headers = [$key => [$host]] + $request->headers;
                    break;
                }
            }

            if( ! $request->hasHeader('host') ){
                $request->headers = ['Host' => [$host]] + $request->headers;
            }
        }

        return $request;
    }
}