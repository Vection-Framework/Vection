<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http\Psr\Message\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Vection\Component\Http\Common\Headers;
use Vection\Component\Http\Psr\Message\Response;
use Vection\Component\Http\Status;

/**
 * Class ResponseFactory
 *
 * @package Vection\Component\Http\Psr\Message\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ResponseFactory implements ResponseFactoryInterface
{

    /**
     * Create a new response.
     *
     * @param int    $code         HTTP status code; defaults to 200
     * @param string $reasonPhrase Reason phrase to associate with status code
     *                             in generated response; if none is provided implementations MAY use
     *                             the defaults as suggested in the HTTP specification.
     *
     * @return ResponseInterface
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        $headers = new Headers();

        if ( ! $reasonPhrase ) {
            $reasonPhrase = Status::getPhrase($code);
        }

        return new Response($code, $headers, null, '1.1', $reasonPhrase);
    }
}
