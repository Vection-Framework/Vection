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

use JsonException;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Common\Headers;
use Vection\Component\Http\Psr\Message\ServerRequest;
use Vection\Component\Http\Server\Environment;
use Vection\Component\Http\Common\Factory\HeadersFactory;

/**
 * Class ServerRequestFactory
 *
 * @package Vection\Component\Http\Psr\Message\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    protected StreamFactoryInterface $streamFactory;
    protected UploadedFileFactory $uploadedFileFactory;

    /**
     * ServerRequestFactory constructor.
     *
     * @param StreamFactoryInterface|null $streamFactory
     * @param UploadedFileFactoryInterface|null $uploadedFileFactory
     */
    public function __construct(
        StreamFactoryInterface $streamFactory = null, UploadedFileFactoryInterface $uploadedFileFactory = null
    )
    {
        $this->streamFactory       = $streamFactory ?: new StreamFactory();
        $this->uploadedFileFactory = $uploadedFileFactory ?: new UploadedFileFactory();
    }

    /**
     * Create a new server request.
     *
     * Note that server-params are taken precisely as given - no parsing/processing
     * of the given values is performed, and, in particular, no attempt is made to
     * determine the HTTP method or URI, which must be provided explicitly.
     *
     * @param string              $method       The HTTP method associated with the request.
     * @param string|UriInterface $uri          The URI associated with the request. If
     *                                          the value is a string, the factory MUST create a UriInterface
     *                                          instance based on it.
     * @param array               $serverParams Array of SAPI parameters with which to seed
     *                                          the generated request instance.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest(string $method, UriInterface|string $uri, array $serverParams = []): ServerRequestInterface
    {
        $environment    = new Environment($serverParams);
        $headersFactory = new HeadersFactory($environment);

        $method        = strtoupper($method);
        $headers       = $headersFactory->createHeaders();
        $version       = explode('/', $environment->getServerProtocol())[1];
        $stream        = $this->streamFactory->createStreamFromFile('php://input');
        $parsedBody    = $this->parseBody($method, $stream, $headers);
        $uploadedFiles = $this->createUploadedFiles();

        return (new ServerRequest($method, $uri, $headers, $version, $environment))
            ->withBody($stream)
            ->withParsedBody($parsedBody)
            ->withUploadedFiles($uploadedFiles)
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE);
    }

    /**
     * Returns an array contains objects from type UploadedFile.
     *
     * @return array
     */
    protected function createUploadedFiles(): array
    {
        $uploadedFiles = [];

        foreach ( ($_FILES ?? []) as $index => $info ) {

            if ( is_array($info['name']) ) {
                $uploadedFiles[$index] = [];

                for ( $i = 0, $c = count($info['name']); $i < $c; $i++) {

                    if ((int) $info['error'][$i] === 0) {
                        $stream = $this->streamFactory->createStreamFromFile($info['tmp_name'][$i]);
                    } else {
                        $stream = $this->streamFactory->createStream();
                    }

                    $uploadedFiles[$index][] = $this->uploadedFileFactory->createUploadedFile(
                        $stream,
                        $info['size'][$i],
                        $info['error'][$i],
                        $info['name'][$i],
                        $info['type'][$i]
                    );
                }
            } else {
                if ((int) $info['error'] === 0) {
                    $stream = $this->streamFactory->createStreamFromFile($info['tmp_name']);
                } else {
                    $stream = $this->streamFactory->createStream();
                }
                $uploadedFiles[$index] = $this->uploadedFileFactory->createUploadedFile(
                    $stream,
                    $info['size'],
                    $info['error'],
                    $info['name'],
                    $info['type']
                );
            }
        }

        return $uploadedFiles;
    }

    /**
     * @param string $method
     * @param StreamInterface $stream
     * @param Headers $headers
     *
     * @return array
     */
    protected function parseBody(string $method, StreamInterface $stream, Headers $headers): array
    {
        $postHeaders = ['application/x-www-form-urlencoded', 'multipart/form-data'];
        $contentType = explode(';', $headers->getLine('content-type'))[0];

        if ($method === 'POST' && in_array($contentType, $postHeaders, true)) {
            return $_POST;
        }

        $content = trim($stream->getContents());

        if (!empty($content )) {
            if ($contentType === 'application/json') {
                try {
                    return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
                }
                catch (JsonException $e) {
                    return [$content];
                }
            }

            return [$content];
        }

        return [];
    }
}
