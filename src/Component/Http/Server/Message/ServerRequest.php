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

namespace Vection\Component\Http\Server\Message;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use stdClass;
use Vection\Component\Http\Common\Headers;
use Vection\Component\Http\Psr\Message\ServerRequest as PsrServerRequest;
use Vection\Component\Http\Server\UserClient;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Server\Factory\UserClientFactory;
use Vection\Component\Http\Server\Factory\ProxyFactory;
use Vection\Component\Http\Server\Proxy;

/**
 * Class ServerRequest
 *
 * @package Vection\Component\Http\Server\Message
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ServerRequest extends PsrServerRequest
{
    protected array $pathParts = [];
    protected string     $contextPath = '/';
    protected UserClient $client;
    protected ?Proxy     $proxy = null;
    protected array $trustedProxies = [];
    protected array $trustedProxyHeaders = [
        Proxy::HEADER_FORWARDED,
        Proxy::HEADER_X_FORWARDED_FOR,
        Proxy::HEADER_X_FORWARDED_HOST,
        Proxy::HEADER_X_PROXYUSER_IP
    ];

    /**
     * @inheritDoc
     */
    public function __construct(
        string $method, UriInterface $uri, Headers $headers, string $version = '1.1', Environment|null $environment = null)
    {
        parent::__construct($method, $uri, $headers, $version, $environment);

        if ( $this->isFromProxy() ) {
            $this->proxy = ProxyFactory::create($this->environment);
        }

        $this->client = UserClientFactory::create($this->environment, $this->proxy);
    }

    /**
     * @param string $ip
     */
    public function addTrustedProxy(string $ip): void
    {
        if ( ! filter_var($ip, FILTER_VALIDATE_IP) ) {
            throw new InvalidArgumentException("Invalid IP address: $ip");
        }

        $this->trustedProxies[$ip] = $ip;
    }

    /**
     * @param array $ips
     */
    public function setTrustedProxies(array $ips): void
    {
        foreach ( $ips as $ip ) {
            $this->addTrustedProxy($ip);
        }
    }

    /**
     * Sets the headers which are indicates a proxy and its values.
     * If this method is not used, all headers are allowed by default.
     *
     * @param array $headerNames
     */
    public function setTrustedProxyHeaders(array $headerNames): void
    {
        foreach ( $headerNames as $name ) {
            $this->trustedProxyHeaders[] = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        }
    }

    /**
     * @return bool
     */
    public function isFromProxy(): bool
    {
        return count(array_intersect(array_keys($this->getServerParams()), $this->trustedProxyHeaders)) > 0 ;
    }

    /**
     * @return bool
     */
    public function isFromTrustedProxy(): bool
    {
        if ( $this->proxy && $this->trustedProxies ) {
            foreach ( $this->proxy->getProxyIPChain() as $ip ) {
                if ( ! isset($this->trustedProxies[$ip]) ) {
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
     * @return UserClient
     */
    public function getClient(): UserClient
    {
        return $this->client;
    }

    /**
     *
     * @return string
     */
    public function getHost(): string
    {
        if ( $this->proxy && $this->isFromTrustedProxy() ) {
            return $this->proxy->getOriginHost();
        }

        return $this->getUri()->getHost();
    }

    /**
     *
     * @return int
     */
    public function getPort(): int
    {
        if ( $this->proxy && $this->isFromTrustedProxy() ) {
            return $this->proxy->getOriginPort();
        }

        return $this->getUri()->getPort();
    }

    /**
     *
     * @return string
     */
    public function getScheme(): string
    {
        $scheme = $this->getUri()->getScheme();

        if ( $this->proxy && ($s = $this->proxy->getOriginProtocol()) ) {
            $scheme = $s;
        }

        return $scheme;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getQueryParam(string $name): ? string
    {
        $value = ($this->getQueryParams()[$name] ?? null);
        return is_string($value) ? $value : null;
    }

    /**
     * @param string $name
     *
     * @return array|null
     */
    public function getQueryArray(string $name): ? array
    {
        $value = ($this->getQueryParams()[$name] ?? null);
        return is_array($value) ? $value : null;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getBodyParam(string $name): mixed
    {
        $body = $this->getParsedBody();

        if ( is_array($body) ) {
            return ($body[$name] ?? null);
        }

        if ( is_object($body) ) {
            return ($body->$name ?? null);
        }

        return null;
    }

    /**
     * @return array
     */
    public function getParsedBodyArray(): array
    {
        $body = $this->getParsedBody();

        return (is_array($body) ? $body : []);
    }

    /**
     * @return object
     */
    public function getParsedBodyObject(): object
    {
        $body = $this->getParsedBody();

        return (is_object($body) ? $body : new stdClass());
    }

    /**
     * @param int $index
     *
     * @return string|null
     */
    public function getPathSegment(int $index): ? string
    {
        return ($this->getPathSegments()[$index] ?? null);
    }

    /**
     * @return array
     */
    public function getPathSegments(): array
    {
        if ( ! $this->pathParts ) {
            $this->pathParts = array_values(array_filter(explode('/', $this->getPath())));
        }

        return $this->pathParts;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        $path = $this->getUri()->getPath();

        if ( $this->contextPath !== '/' && str_starts_with($path, $this->contextPath)) {
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
        $this->pathParts   = [];
    }

    /**
     * Adds a context path to the existing one.
     *
     * @param string $contextPath
     */
    public function addContextPath(string $contextPath): void
    {
        $slash = $this->contextPath === '/' ? '' : '/';
        $this->contextPath .= $slash . trim($contextPath, '/');
        $this->pathParts    = [];
    }

    /**
     * @return string
     */
    public function getContextPath(): string
    {
        return $this->contextPath;
    }
}
