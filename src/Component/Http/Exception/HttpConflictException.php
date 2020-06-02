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
 * Class HttpConflictException
 *
 * @package Vection\Component\Http\Exception
 */
class HttpConflictException extends HttpException
{
    /**
     * HttpConflictException constructor.
     *
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', \Throwable $previous = null)
    {
        parent::__construct($message ?: 'Conflict', 409, $previous);
    }
}