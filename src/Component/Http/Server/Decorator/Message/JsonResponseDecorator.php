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

namespace Vection\Component\Http\Server\Decorator\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class JsonResponseDecorator
 *
 * @package Vection\Component\Http\Server\Decorator\Message
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class JsonResponseDecorator implements ResponseInterface
{

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @var array
     */
    protected $data;

    /**
     * JsonResponse constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response->withHeader('Content-Type', ['application/json; charset=utf-8']);
        $this->data     = [];
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @param array $data
     */
    public function mergeData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param string $key
     * @param string|array $value
     */
    public function addData(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name): bool
    {
        return $this->response->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name): array
    {
        return $this->response->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name): string
    {
        return $this->response->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        $body = $this->response->getBody();

        if ( $body->getSize() === 0 ) {
            $body->write(json_encode($this->data, JSON_PARTIAL_OUTPUT_ON_ERROR|JSON_INVALID_UTF8_IGNORE));
        }

        return $body;
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        $this->response = $this->response->withProtocolVersion($version);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $this->response = $this->response->withHeader($name, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        $this->response = $this->response->withAddedHeader($name, $value);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $this->response = $this->response->withoutHeader($name);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $this->response = $this->response->withBody($body);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $this->response = $this->response->withStatus($code, $reasonPhrase);
        return $this;
    }
}
