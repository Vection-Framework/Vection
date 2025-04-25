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

namespace Vection\Component\Http\Psr\Message\Factory;

use InvalidArgumentException;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Common\Headers;
use Vection\Component\Http\Psr\Message\Request;

/**
 * Class RequestFactory
 *
 * @package Vection\Component\Http\Psr\Message\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class RequestFactory implements RequestFactoryInterface
{
    protected UriFactoryInterface $uriFactory;

    /**
     * RequestFactory constructor.
     *
     * @param UriFactoryInterface|null $uriFactory
     */
    public function __construct(UriFactoryInterface|null $uriFactory = null)
    {
        $this->uriFactory = $uriFactory ?: new UriFactory();
    }

    /**
     * Create a new request.
     *
     * @param string              $method The HTTP method associated with the request.
     * @param string|UriInterface $uri    The URI associated with the request. If
     *                                    the value is a string, the factory MUST create a UriInterface
     *                                    instance based on it.
     *
     * @return RequestInterface
     */
    public function createRequest(string $method, $uri): RequestInterface
    {
        if ( is_string($uri) ) {
            $uri = $this->uriFactory->createUri($uri);
        }

        if ( ! $uri instanceof UriInterface) {
            throw new InvalidArgumentException(
                'Except parameter 2 to be a string or an instance of UriInterface.'
            );
        }

        return new Request($method, $uri, new Headers());
    }
}
