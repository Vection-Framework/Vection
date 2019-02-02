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

namespace Vection\Contracts\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestHandler
 *
 * @package Vection\Contracts\Http
 */
interface RequestHandlerInterface
{

    /**
     * Adds a middleware to the stack.
     *
     * @param RequestMiddlewareInterface $middleware
     */
    public function addMiddleware(RequestMiddlewareInterface $middleware): void;

    /**
     * Returns the response object.
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface;

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}