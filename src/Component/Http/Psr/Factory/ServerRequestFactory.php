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
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriInterface;
use Vection\Component\Http\Headers;
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
     * @param StreamFactoryInterface       $streamFactory
     * @param UploadedFileFactoryInterface $uploadedFileFactory
     */
    public function __construct(
        StreamFactoryInterface $streamFactory = null, UploadedFileFactoryInterface $uploadedFileFactory = null
    )
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
        $method = strtoupper($method);
        $headers = $this->createHeaders();
        $environment = new Environment($serverParams);
        $version = explode('/', $environment->getServerProtocol())[1];
        $stream = $this->streamFactory->createStreamFromFile('php://input');
        $parsedBody = $this->parseBody($method, $stream, $headers);
        $uploadedFiles = $this->createUploadedFiles();

        $request = (new ServerRequest($method, $uri, $headers, $version, $environment))
            ->withBody($stream)
            ->withParsedBody($parsedBody)
            ->withUploadedFiles($uploadedFiles)
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE)
        ;

        return $request;
    }

    /**
     * Creates an object from type Header that contains all headers
     * send by the client to server.
     *
     * @return Headers
     */
    protected function createHeaders(): Headers
    {
        # Create header parameters
        $headers = new Headers();

        # Content info does not appear in $_SERVER as $_SERVER['HTTP_CONTENT_TYPE'].
        # PHP removes these (per CGI/1.1 specification[1]) from the HTTP_ match group
        $exceptionalHeaders = [
            'CONTENT_TYPE' => 'content-type',
            'CONTENT_LENGTH' => 'content-length',
            'CONTENT_MD5' => 'content-md5'
        ];

        foreach( $_SERVER as $name => $value ){
            if( stripos($name, 'HTTP_') === 0 ){
                $name = substr(strtolower(str_replace('_', '-', $name)), 5);
                $headers->set($name, $value);
            }elseif(isset($exceptionalHeaders[$name])){
                $headers->set($exceptionalHeaders[$name], $value);
            }
        }

        if( ! $headers->has('Authorization') && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) ){
            $headers->set('Authorization', $_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
        }

        $authUser = trim($_SERVER['PHP_AUTH_USER'] ?? '');
        $authPw = trim($_SERVER['PHP_AUTH_PW'] ?? '');

        if( $authUser ){
            $headers->set('Authorization', 'Basic '.base64_encode($authUser.':'.$authPw));
        }

        return $headers;
    }

    /**
     * Returns an array contains objects from type UploadedFile.
     *
     * @return array
     */
    protected function createUploadedFiles(): array
    {
        $uploadedFiles = [];

        foreach( $_FILES ?? [] as $index => $info ){

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

        return $uploadedFiles;
    }

    /**
     * @param string           $method
     * @param StreamInterface  $stream
     * @param Headers $headers
     *
     * @return array
     */
    protected function parseBody(string $method, StreamInterface $stream, Headers $headers): array
    {
        $postHeaders = ['application/x-www-form-urlencoded', 'multipart/form-data'];

        if( $method === 'POST' && in_array($headers->getContentType(), $postHeaders) ){
            return $_POST;
        }

        $content = trim($stream->getContents());

        if( ! empty($content) ){

            if( $headers->getContentType() === 'application/json' ){
                $data = json_decode($content, true);
                return json_last_error() !== JSON_ERROR_NONE ? [] : $data;
            }

            return [$content];

        }

        return [];
    }
}