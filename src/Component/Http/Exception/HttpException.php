<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Http\Exception;

use RuntimeException;
use Vection\Component\Http\Status;

/**
 * Class HttpException
 *
 * @package Vection\Component\Http\Exception
 */
class HttpException extends RuntimeException
{
    /**
     * Returns the http status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return (int) $this->getCode();
    }

    /**
     * Returns the status code related text.
     * 
     * @return string
     */
    public function getStatusText(): string
    {
        return Status::PHRASES[$this->getStatusCode()];
    }

    /**
     * Returns the status code with text.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->getStatusCode().' '.$this->getStatusText();
    }

    /**
     * @return string
     */
    public function getCodeString(): string
    {
        return str_replace('Exception', '', basename(str_replace('\\', '/', get_class($this))));
    }

    /**
     * @return string
     */
    public function getStatusType(): string
    {
        $statusCode = $this->getStatusCode();

        if( $statusCode >= 100 && $statusCode < 200 ){
            return 'Information';
        }

        if( $statusCode >= 200 && $statusCode < 300 ){
            return 'Success';
        }

        if( $statusCode >= 300 && $statusCode < 400 ){
            return 'Redirect';
        }

        if( $statusCode >= 400 && $statusCode < 500 ){
            return 'ClientError';
        }

        if( $statusCode >= 500 && $statusCode < 600 ){
            return 'ServerError';
        }

        return 'Unknown';
    }

    /**
     * Returns this exception as string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getStatus() . ($this->message ? ' - '. $this->message : '');
    }

}