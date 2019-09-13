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

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Headers;
use Vection\Component\Http\Psr\ServerRequest;
use Vection\Component\Http\Server\Factory\ClientFactory;
use Vection\Component\Http\Server\Factory\ProxyFactory;

/**
 * class Request
 * @package Vection\Component\Http\Server
 */
class Request extends ServerRequest
{
    /** @var array */
    protected $pathParts = [];

    /** @var string */
    protected $contextPath = '/';

    /** @var Client */
    protected $client;

    /** @var Proxy */
    protected $proxy;

    /** @var array */
    protected $trustedProxies = [];

    /**
     * Request constructor.
     *
     * @param string           $method
     * @param UriInterface     $uri
     * @param Headers          $headers
     * @param string           $version
     * @param Environment|null $environment
     */
    public function __construct(
        string $method, UriInterface $uri, Headers $headers, string $version = '1.1', Environment $environment = null
    )
    {
        parent::__construct($method, $uri, $headers, $version, $environment);

        if( $this->isFromProxy() ){
            $this->proxy = ProxyFactory::create($this->environment);
            $this->client = ClientFactory::create($this->environment, $this->proxy);
        }else{
            $this->client = ClientFactory::create($this->environment);
        }
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
     * @return bool
     */
    public function isFromProxy(): bool
    {
        return count(array_intersect(array_keys($this->environment->toArray()), [
            'HTTP_FORWARDED','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED_HOST', 'HTTP_X_PROXYUSER_IP'
        ])) > 0 ;
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

        return $this->uri->getHost();
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

        return $this->uri->getPort();
    }

    /**
     *
     * @return string
     */
    public function getScheme(): string
    {
        $scheme = $this->uri->getScheme();

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
    public function getPathPart(int $index): ? string
    {
        if( ! $this->pathParts && ($path = $this->uri->getPath()) !== '/' ){
            $this->pathParts = array_values(array_filter(explode('/', $path)));
        }

        return $this->pathParts[$index] ?? null;
    }

    /**
     * @return array
     */
    public function getPathParts(): array
    {
        if( ! $this->pathParts && ($path = $this->uri->getPath()) !== '/' ){
            $this->pathParts = array_values(array_filter(explode('/', $path)));
        }

        return $this->pathParts;
    }

    /**
     * @param string $contextPath
     */
    public function setContextPath(string $contextPath): void
    {
        $this->contextPath = '/' . ltrim($contextPath, '/');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
         $path = $this->uri->getPath();

         if( strpos($path, $this->contextPath) === 0 ){
             $path = substr($path, strlen($this->contextPath));
         }

         return $path;
    }

    /**
     * @return string
     */
    public function getContextPath(): string
    {
        return $this->contextPath;
    }

    /**
     * @return string
     */
    public function getContextPathName(): string
    {
        return substr($this->contextPath, 1);
    }

}