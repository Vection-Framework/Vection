<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ResponderInterface
 *
 * @package Vection\Contracts\Http\Server
 */
interface ResponderInterface
{
    /**
     * @param ResponseInterface      $response
     * @param ServerRequestInterface $request
     */
    public function send(ResponseInterface $response, ServerRequestInterface $request): void;
}