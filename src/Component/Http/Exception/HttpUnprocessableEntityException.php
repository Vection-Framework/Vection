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
 * Class HttpPreconditionFailedException
 *
 * @package Vection\Component\Http\Exception
 */
class HttpUnprocessableEntityException extends HttpException
{
    /**
     * HttpUnprocessableEntityException constructor.
     *
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message ?: 'Unprocessable Entity', 422, $previous);
    }
}