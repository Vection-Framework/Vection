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

namespace Vection\Component\Http\Psr\Message;

use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Vection\Component\Http\Headers;
use Vection\Component\Http\Status;

/**
 * Class Response
 *
 * @package Vection\Component\Http\Psr\Message
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Response extends Message implements ResponseInterface
{

    /** @var integer */
    protected $statusCode;

    /** @var string */
    protected $reasonPhrase;

    /**
     * Response constructor.
     *
     * @param int                  $status
     * @param Headers|null         $headers
     * @param StreamInterface|null $body
     * @param string               $version
     * @param string               $reasonPhrase
     */
    public function __construct(
        int $status = 200, Headers $headers = null, ? StreamInterface $body = null, string $version = '1.1', string $reasonPhrase = ''
    )
    {
        parent::__construct($headers ?: new Headers(), $body, $version);
        $this->statusCode   = $status;
        $this->reasonPhrase = $reasonPhrase ?: Status::getPhrase($status);
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * Because a reason phrase is not a required element in a response
     * status line, the reason phrase value MAY be null. Implementations MAY
     * choose to return the default RFC 7231 recommended reason phrase (or those
     * listed in the IANA HTTP Status Code Registry) for the response's
     * status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * If no reason phrase is specified, implementations MAY choose to default
     * to the RFC 7231 or IANA recommended reason phrase for the response's
     * status code.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * updated status and reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     *
     * @param int    $code         The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *                             provided status code; if none is provided, implementations MAY
     *                             use the defaults as suggested in the HTTP specification.
     *
     * @return static
     * @throws InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = ''): Response
    {
        $response = clone $this;
        $response->statusCode = $code;

        if ( ! $reasonPhrase ) {
            $response->reasonPhrase = Status::getPhrase($code);
        } else {
            $response->reasonPhrase = $reasonPhrase;
        }

        return $response;
    }
}
