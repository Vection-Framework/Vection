<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vection\Contracts\Http\RequestHandlerInterface;
use Vection\Contracts\Http\RequestMiddlewareInterface;

/**
 * Class RequestHandler
 *
 * @package Vection\Component\Http
 */
class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var RequestMiddlewareInterface[]
     */
    protected $middleware;

    /**
     * RequestHandler constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * Adds a middleware to the stack.
     *
     * @param RequestMiddlewareInterface $middleware
     */
    public function addMiddleware(RequestMiddlewareInterface $middleware): void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Returns the response object.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
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

        return $this->response;
    }
}