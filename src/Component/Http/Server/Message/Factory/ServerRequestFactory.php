<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Server\Message\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Vection\Component\Http\Psr\Message\Factory\ServerRequestFactory as PsrServerRequestFactory;
use Vection\Component\Http\Server\Factory\UriFactory;
use Vection\Component\Http\Server\Message\ServerRequest;
use Vection\Component\Http\Server\Environment;

/**
 * Class ServerRequestFactory
 *
 * @package Vection\Component\Http\Server\Factory
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ServerRequestFactory extends PsrServerRequestFactory
{
    /**
     * @param Environment|null $environment
     *
     * @return ServerRequest
     */
    public function createServerRequestFromEnvironment(Environment $environment = null): ServerRequestInterface
    {
        if ( ! $environment ) {
            $environment = new Environment();
        }

        $uriFactory = new UriFactory();

        return $this->createServerRequest(
            $environment->getRequestMethod(),
            $uriFactory->createUriFromEnvironment(),
            $environment->toArray()
        );
    }
}
