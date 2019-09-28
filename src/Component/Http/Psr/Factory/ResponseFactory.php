<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http\Psr\Factory;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Vection\Component\Http\Headers;
use Vection\Component\Http\Psr\Response;
use Vection\Component\Http\Status;

/**
 * Class ResponseFactory
 *
 * @package Vection\Component\Http\Psr\Factory
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

        if( ! $reasonPhrase ){
            $reasonPhrase = Status::getPhrase($code);
        }

        return new Response($code, $headers, null, '1.1', $reasonPhrase);
    }
}