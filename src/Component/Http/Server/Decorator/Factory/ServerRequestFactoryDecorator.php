<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Server\Decorator\Factory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Psr\Message\Factory\UriFactory;
use Vection\Component\Http\Server\Decorator\Message\ServerRequestDecorator;
use Vection\Component\Http\Server\Environment;

/**
 * Class ServerRequestFactory
 *
 * @package Vection\Component\Http\Server\Decorator\Factory
 */
class ServerRequestFactoryDecorator implements ServerRequestFactoryInterface
{

    /**
     * @var ServerRequestFactoryInterface
     */
    protected $requestFactory;

    /**
     * ServerRequestFactory constructor.
     *
     * @param ServerRequestFactoryInterface $requestFactory
     */
    public function __construct(ServerRequestFactoryInterface $requestFactory)
    {
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param Environment|null $environment
     *
     * @return ServerRequestDecorator
     */
    public function createFromGlobals(Environment $environment = null): ServerRequestInterface
    {
        if ( ! $environment ) {
            $environment = new Environment();
        }

        $uriFactory = new UriFactoryDecorator(new UriFactory());

        return $this->createServerRequest(
            $environment->getRequestMethod(),
            $uriFactory->createFromGlobals(),
            $environment->toArray()
        );
    }

    /**
     * Create a new server request.
     *
     * Note that server-params are taken precisely as given - no parsing/processing
     * of the given values is performed, and, in particular, no attempt is made to
     * determine the HTTP method or URI, which must be provided explicitly.
     *
     * @param string              $method       The HTTP method associated with the request.
     * @param UriInterface|string $uri          The URI associated with the request. If
     *                                          the value is a string, the factory MUST create a UriInterface
     *                                          instance based on it.
     * @param array               $serverParams Array of SAPI parameters with which to seed
     *                                          the generated request instance.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, $uri, array $serverParams = []): ServerRequestInterface
    {
        return $this->requestFactory->createServerRequest($method, $uri, $serverParams);
    }
}
