<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Exception;

/**
 * Class JsonException
 *
 * @package Vection\Component\Utility\IO\Exception
 */
class JsonException extends \Exception
{
    public const NOT_FOUND = 10;
    public const INVALID   = 11;

    /**
     * JsonException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(( $message ? $message . "\n Error: " : '' ) . \json_last_error_msg(), $code, $previous);
    }
}
