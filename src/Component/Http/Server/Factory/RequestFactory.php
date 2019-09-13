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

namespace Vection\Component\Http\Server\Factory;

use Vection\Component\Http\Psr\Factory\ServerRequestFactory;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Server\Request;

/**
 * Class RequestFactory
 *
 * @package Vection\Component\Http\Server\Factory
 */
class RequestFactory extends ServerRequestFactory
{
    /**
     * @return Request
     */
    public function createFromGlobals(): Request
    {
        $environment = new Environment($_SERVER);
        $uriFactory = new UriFactory($environment);
        $headersFactory = new HeadersFactory($environment);

        $method = $environment->getRequestMethod();
        $uri = $uriFactory->createUri();
        $headers = $headersFactory->createHeaders();

        $version = explode('/', $environment->getServerProtocol())[1];

        $request = new Request($method, $uri, $headers, $version, $environment);

        $stream = $this->streamFactory->createStreamFromFile('php://input');
        $parsedBody = $this->parseBody($method, $stream, $headers);
        $uploadedFiles = $this->createUploadedFiles();

        $request = $request
            ->withBody($stream)
            ->withParsedBody($parsedBody)
            ->withUploadedFiles($uploadedFiles)
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE)
        ;

        return $request;
    }

}