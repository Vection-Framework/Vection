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

namespace Vection\Component\Http\Psr\Client;

use Exception;
use Psr\Http\Client\NetworkExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * Class NetworkException
 *
 * @package Vection\Component\Http\Psr\Client
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class NetworkException extends Exception implements NetworkExceptionInterface
{
    protected RequestInterface $request;

    /**
     * NetworkException constructor.
     *
     * @param RequestInterface $request
     * @param string           $message
     * @param int              $code
     * @param Throwable|null   $previous
     */
    public function __construct(RequestInterface $request, $message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    /**
     * Returns the request.
     *
     * The request object MAY be a different object from the one passed to ClientInterface::sendRequest()
     *
     * @return RequestInterface
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
