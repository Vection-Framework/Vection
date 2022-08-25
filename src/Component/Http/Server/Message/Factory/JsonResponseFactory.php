<?php

declare(strict_types=1);

namespace Vection\Component\Http\Server\Message\Factory;

use JsonSerializable;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Vection\Component\Http\Psr\Message\Factory\ResponseFactory;
use Vection\Component\Http\Psr\Message\Factory\StreamFactory;

/**
 * Class ResponseFactory
 *
 * @package Vection\Component\Http\Server\Factory
 * @author  David Lung <david.lung@appsdock.de>
 */
class JsonResponseFactory
{
    protected ResponseFactoryInterface|null $responseFactory;

    /**
     * JsonResponseFactory constructor.
     *
     * @param ResponseFactoryInterface|null $responseFactory
     */
    public function __construct(ResponseFactoryInterface|null $responseFactory = null)
    {
        $this->responseFactory = $responseFactory ?: new ResponseFactory();
    }

    /**
     * @param int                    $statusCode
     * @param array|JsonSerializable $data
     * @param array                  $headers
     * @param int                    $jsonFlags
     *
     * @return ResponseInterface
     */
    public function createResponse(
        int $statusCode = 200, array|JsonSerializable $data = [], array $headers = [],
        int $jsonFlags = JSON_PARTIAL_OUTPUT_ON_ERROR|JSON_INVALID_UTF8_IGNORE): ResponseInterface
    {
        $response = $this->responseFactory->createResponse($statusCode);

        foreach( $headers as $name => $value ){
            $response = $response->withHeader($name, $value);
        }

        $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');

        $body = (new StreamFactory())->createStream(json_encode($data, $jsonFlags));

        return $response->withBody($body);
    }
}
