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

namespace Vection\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Vection\Component\Http\Factory\ResponseFactory;
use Vection\Component\Http\Factory\ServerRequestFactory;

/**
 * Class Kernel
 *
 * @package Vection\Component\Http
 */
class Kernel implements RequestHandlerInterface
{
    /** @var ServerRequestInterface */
    protected $request;

    /** @var MiddlewareInterface[] */
    protected $middleware;

    /**
     * Kernel constructor.
     *
     * @param ServerRequestInterface|null $request
     */
    public function __construct(ServerRequestInterface $request = null)
    {
        $this->request = $request ?: ServerRequestFactory::createFromGlobals();
    }

    /**
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if( $middleware = current($this->middleware) ){
            next($this->middleware);
            return $middleware->process($request, $this);
        }

        # None the middleware have returned a response, so there is no processed content
        $response = ResponseFactory::createFromGlobals();
        $response->withStatus(501);
        return $response;
    }
}