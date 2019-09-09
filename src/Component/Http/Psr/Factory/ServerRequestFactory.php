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

namespace Vection\Component\Http\Psr\Factory;

use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Factory\HeadersFactory;
use Vection\Component\Http\Psr\ServerRequest;
use Vection\Component\Http\Server\Environment;

/**
 * Class ServerRequestFactory
 *
 * @package Vection\Component\Http\Psr\Factory
 */
class ServerRequestFactory implements ServerRequestFactoryInterface
{
    /** @var StreamFactoryInterface */
    protected $streamFactory;

    /** @var UploadedFileFactory */
    protected $uploadedFileFactory;

    /**
     * ServerRequestFactory constructor.
     *
     * @param StreamFactoryInterface $streamFactory
     * @param UploadedFileFactory    $uploadedFileFactory
     */
    public function __construct(StreamFactoryInterface $streamFactory = null, UploadedFileFactory $uploadedFileFactory = null)
    {
        $this->streamFactory = $streamFactory ?: new StreamFactory();
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
        $environment = new Environment($serverParams);
        $headers = (new HeadersFactory())->createFromServer();
        $version = explode('/', $environment->getServerProtocol())[1];

        $request = new ServerRequest($method, $uri, $headers, $version, $environment);

        if( $_FILES ){
            $uploadedFiles = [];

            foreach( $_FILES as $index => $info ){

                if( is_array($info['name']) ){
                    $uploadedFiles[$index] = [];

                    for($i = 0; $i < count($info['name']); $i++){
                        $stream = $this->streamFactory->createStreamFromFile($info['tmp_name'][$i]);
                        $uploadedFiles[$index][] = $this->uploadedFileFactory->createUploadedFile(
                            $stream, $info['size'][$i], $info['error'][$i], $info['tmp_name'][$i], $info['type'][$i]
                        );
                    }
                }else{
                    $stream = $this->streamFactory->createStreamFromFile($info['tmp_name']);
                    $uploadedFiles[$index] = $this->uploadedFileFactory->createUploadedFile(
                        $stream, $info['size'], $info['error'], $info['tmp_name'], $info['type']
                    );
                }
            }

            $request = $request->withUploadedFiles($uploadedFiles);
        }

        $postHeaders = ['application/x-www-form-urlencoded', 'multipart/form-data'];

        if( strtolower($method) === 'post' && in_array( $headers->get('content-type'), $postHeaders) ){
            $request = $request->withParsedBody($_POST);
        }else{
            $stream = $this->streamFactory->createStreamFromFile('php://input');

            if( ! empty($content = $stream->getContents()) ){

                if( stripos($headers->getLine('content-type'), 'application/json') === 0 ){
                    $parsedBody = json_decode($content, true);
                }else{
                    // TODO add more content type based parsing
                    $parsedBody = [];
                    parse_str($content, $parsedBody);
                }
            }
        }

        return $request;
    }
}