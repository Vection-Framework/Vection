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

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 *
 * @package Vection\Component\Http\Psr\Client
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Client implements ClientInterface
{

    /**
     * Sends a PSR-7 request and returns a PSR-7 response.
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     *
     * @throws ClientExceptionInterface If an error happens while processing the request.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        # TODO ===================================================
        # TODO
        # TODO  THIS CODE IS ONLY TEMPORARY AND SHOULD BE CHANGED
        # TODO
        # TODO ===================================================

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $request->getUri());
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request->getMethod());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if ($request->getMethod() === 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $request->getBody()->getContents());
        }

        $headers = [];

        foreach ($request->getHeaders() as $name => $values) {
            $headers[] = sprintf('%s: %s', $name, implode(', ', $values));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($curl);

        curl_close($curl);


    }

}