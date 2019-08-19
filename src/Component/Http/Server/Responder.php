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

namespace Vection\Component\Http\Server;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Responder
 *
 * @package Vection\Component\Http\Server
 */
class Responder
{
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Responder constructor.
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     *
     */
    public function send(): void
    {

    }
}