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
use Psr\Http\Server\MiddlewareInterface;
use RuntimeException;
use Vection\Contracts\Http\Server\RequestHandlerInterface;

/**
 * Class RequestHandler
 *
 * @package Vection\Component\Http\Server
 */
class RequestHandler implements RequestHandlerInterface
{
    /** @var MiddlewareInterface[] */
    protected $middleware;

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
        throw new RuntimeException(
            'HTTP-Kernel: Expects exact one middleware that returns an response, none one does.'
        );
    }
}