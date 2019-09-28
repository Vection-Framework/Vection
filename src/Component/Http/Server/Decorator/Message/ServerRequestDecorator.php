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

namespace Vection\Component\Http\Server\Decorator\Message;

use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Server\Client;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Server\Factory\ClientFactory;
use Vection\Component\Http\Server\Factory\ProxyFactory;
use Vection\Component\Http\Server\Proxy;

/**
 * Class ServerRequestDecorator
 *
 * @package Vection\Component\Http\Server\Decorator\Message
 */
class ServerRequestDecorator implements ServerRequestInterface
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $pathParts = [];

    /**
     * @var string
     */
    protected $contextPath = '/';

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Proxy
     */
    protected $proxy;

    /**
     * @var array
     */
    protected $trustedProxies = [];

    /**
     * @var array
     */
    protected $trustedProxyHeaders = [
        Proxy::HEADER_FORWARDED,
        Proxy::HEADER_X_FORWARDED_FOR,
        Proxy::HEADER_X_FORWARDED_HOST,
        Proxy::HEADER_X_PROXYUSER_IP
    ];

    /**
     * ServerRequestDecorator constructor.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
        $environment = new Environment($request->getServerParams());

        if( $this->isFromProxy() ){
            $this->proxy = ProxyFactory::create($environment);
        }

        $this->client = ClientFactory::create($environment, $this->proxy);
    }

    /**
     * @param string $ip
     */
    public function addTrustedProxy(string $ip): void
    {
        if( ! filter_var($ip, FILTER_VALIDATE_IP) ){
            throw new InvalidArgumentException("Invalid IP address: $ip");
        }

        $this->trustedProxies[$ip] = $ip;
    }

    /**
     * @param array $ips
     */
    public function setTrustedProxies(array $ips): void
    {
        foreach( $ips as $ip ){
            $this->addTrustedProxy($ip);
        }
    }

    /**
     * Sets the headers which are indicate an proxy and its values.
     * If this method is not used, all headers are allowed by default.
     *
     * @param array $headerNames
     */
    public function setTrustedProxyHeaders(array $headerNames): void
    {
        foreach( $headerNames as $name ){
            $this->trustedProxyHeaders[] = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        }
    }

    /**
     * @return bool
     */
    public function isFromProxy(): bool
    {
        return count(array_intersect(array_keys($this->request->getServerParams()), $this->trustedProxyHeaders)) > 0 ;
    }

    /**
     * @return bool
     */
    public function isFromTrustedProxy(): bool
    {
        if( $this->proxy && $this->trustedProxies ){
            foreach( $this->proxy->getProxyIPChain() as $ip ){
                if( ! isset($this->trustedProxies[$ip]) ){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     *
     * @return Proxy|null
     */
    public function getProxy(): ? Proxy
    {
        return $this->proxy;
    }

    /**
     *
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     *
     * @return string
     */
    public function getHost(): string
    {
        if( $this->proxy && $this->isFromTrustedProxy() ){
            return $this->proxy->getOriginHost();
        }

        return $this->request->getUri()->getHost();
    }

    /**
     *
     * @return int
     */
    public function getPort(): int
    {
        if( $this->proxy && $this->isFromTrustedProxy() ){
            return $this->proxy->getOriginPort();
        }

        return $this->request->getUri()->getPort();
    }

    /**
     *
     * @return string
     */
    public function getScheme(): string
    {
        $scheme = $this->request->getUri()->getScheme();

        if( $this->proxy && ($s = $this->proxy->getOriginProtocol()) ){
            $scheme = $s;
        }

        return $scheme;
    }

    /**
     * @param string $name
     *
     * @return string|array|null
     */
    public function getQueryParam(string $name)
    {
        return $this->queryParams[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getBodyParam(string $name)
    {
        $body = $this->getParsedBody();

        if( is_array($body) ){
            return $body[$name] ?? null;
        }

        if( is_object($body) ){
            return $body->$name ?? null;
        }

        return null;
    }

    /**
     * @param int $index
     *
     * @return string|null
     */
    public function getPathSegment(int $index): ? string
    {
        return $this->getPathSegments()[$index] ?? null;
    }

    /**
     * @return array
     */
    public function getPathSegments(): array
    {
        if( ! $this->pathParts ){
            $this->pathParts = array_values(array_filter(explode('/', $this->getPath())));
        }

        return $this->pathParts;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = $this->request->getUri()->getPath();

        if( strpos($path, $this->contextPath) === 0 ){
            $path = substr($path, strlen($this->contextPath));
        }

        return $path;
    }

    /**
     * @param string $contextPath
     */
    public function setContextPath(string $contextPath): void
    {
        $this->contextPath = '/' . strtolower(trim($contextPath, '/'));
        $this->pathParts = [];
    }

    /**
     * Adds an additional context path to the existing one.
     *
     * @param string $contextPath
     */
    public function addContextPath(string $contextPath): void
    {
        $slash = $this->contextPath === '/' ? '' : '/';
        $this->contextPath .= $slash . trim($contextPath, '/');
        $this->pathParts = [];
    }

    /**
     * @return string
     */
    public function getContextPath(): string
    {
        return $this->contextPath;
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->request->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->request->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->request->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        return $this->request->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->request->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->request->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name): bool
    {
        return $this->request->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name): array
    {
        return $this->request->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name): string
    {
        return $this->request->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->request->getBody();
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->request->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->request->getUri();
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->request->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value)
    {
        return new self($this->request->withAttribute($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name)
    {
        return new self($this->request->withoutAttribute($name));
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        return new self($this->request->withProtocolVersion($version));
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data)
    {
        return new self($this->request->withParsedBody($data));
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self($this->request->withUploadedFiles($uploadedFiles));
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        return new self($this->request->withRequestTarget($requestTarget));
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        return new self($this->request->withQueryParams($query));
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->request->withBody($body));
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        return new self($this->request->withCookieParams($cookies));
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->request->withUri($uri, $preserveHost));
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        return new self($this->request->withMethod($method));
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        return new self($this->request->withHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        return new self($this->request->withAddedHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        return new self($this->request->withoutHeader($name));
    }
}