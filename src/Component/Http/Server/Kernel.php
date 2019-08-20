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

use Psr\Http\Message\ServerRequestInterface;
use Vection\Contracts\Http\RequestHandlerInterface;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Kernel
 *
 * @package Vection\Component\Http\Server
 */
class Kernel
{
    /** @var ServerRequestInterface */
    protected $request;

    /** @var RequestHandlerInterface */
    protected $requestHandler;

    /** @var ResponderInterface */
    protected $responder;

    /**
     * Kernel constructor.
     *
     * @param RequestHandlerInterface $requestHandler
     * @param ServerRequestInterface  $request
     * @param ResponderInterface|null $responder
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        ServerRequestInterface $request = null,
        ResponderInterface $responder = null
    )
    {
        $this->requestHandler = $requestHandler;
        $this->request = $request ?: RequestFactory::createFromGlobals();
        $this->responder = $responder ?: new Responder();
    }

    public function execute(): void
    {

        $this->responder->send(
            $this->requestHandler->handle($this->request), $this->request
        );

    }
}