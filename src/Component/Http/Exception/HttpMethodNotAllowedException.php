<?php
/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http\Exception;

/**
 * Class HttpMethodNotAllowedException
 *
 * @package Vection\Component\Http\Exception
 */
class HttpMethodNotAllowedException extends HttpException
{
    /**
     * BadRequestException constructor.
     *
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message, 405, $previous);
    }

    /**
     * @param string $method
     * @throws HttpMethodNotAllowedException
     */
    public static function assertPOST(string $method): void
    {
        if( $method !== 'POST' ){
            throw new static();
        }
    }

    /**
     * @param string $method
     * @throws HttpMethodNotAllowedException
     */
    public static function assertPUT(string $method): void
    {
        if( $method !== 'PUT' ){
            throw new static();
        }
    }

    /**
     * @param string $method
     * @throws HttpMethodNotAllowedException
     */
    public static function assertGET(string $method): void
    {
        if( $method !== 'GET' ){
            throw new static();
        }
    }

    /**
     * @param string $method
     * @throws HttpMethodNotAllowedException
     */
    public static function assertDELETE(string $method): void
    {
        if( $method !== 'DELETE' ){
            throw new static();
        }
    }
}