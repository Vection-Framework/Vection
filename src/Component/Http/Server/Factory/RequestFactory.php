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

use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
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
    /** @var UriFactory */
    protected $uriFactory;

    /**
     * RequestFactory constructor.
     *
     * @param StreamFactoryInterface|null       $streamFactory
     * @param UploadedFileFactoryInterface|null $uploadedFileFactory
     */
    public function __construct(
        StreamFactoryInterface $streamFactory = null, UploadedFileFactoryInterface $uploadedFileFactory = null
    )
    {
        parent::__construct($streamFactory, $uploadedFileFactory);
        $this->uriFactory = new UriFactory();
    }

    /**
     * @return Request
     */
    public function createFromGlobals(): Request
    {
        $method = strtoupper($_SERVER['REQUEST_METHOD']);
        $uri = $this->uriFactory->createUri();
        $headers = $this->createHeaders();
        $environment = new Environment($_SERVER);
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


        #if( ! $authUser && $headers->has('Authorization') ){
        #    $authorization = $headers->getLine('Authorization');

        #    if( stripos($authorization, 'basic ') !== 0 ){
        #        $parts = explode(':', base64_decode(substr($authorization, 6)), 2);
        #        if( count($parts) === 2 ){
        #            list($authUser, $authPw) = $parts;
        #        }
        #    }
        #}
    }
}