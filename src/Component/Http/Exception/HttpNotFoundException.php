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

use Throwable;

/**
 * Class HttpNotFoundException
 *
 * @package Vection\Component\Http\Exception
 */
class HttpNotFoundException extends HttpException
{
    /**
     * @param object|null $resource
     *
     * @throws HttpNotFoundException
     */
    public static function assertFound(? object $resource): void
    {
        if( ! $resource ){
            throw new self();
        }
    }

    /**
     * NotFoundException constructor.
     *
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct($message ?: 'Not Found', 404, $previous);
    }
}