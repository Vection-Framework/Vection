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

namespace Vection\Component\Http\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Vection\Component\Http\Server\Event\AfterSendRequestEvent;
use Vection\Component\Http\Server\Event\BeforeHandleRequestEvent;
use Vection\Component\Http\Server\Event\BeforeSendRequestEvent;
use Vection\Component\Http\Server\Event\BeforeTerminateRequestEvent;
use Vection\Component\Http\Server\Message\Factory\ServerRequestFactory;
use Vection\Contracts\Event\EventDispatcherInterface;
use Vection\Contracts\Http\Server\KernelInterface;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Kernel
 *
 * @package Vection\Component\Http\Server
 * @author  David Lung <david.lung@appsdock.de>
 */
class Kernel implements KernelInterface
{
    protected ServerRequestInterface $request;
    protected RequestHandlerInterface $requestHandler;
    protected ResponderInterface $responder;
    protected LoggerInterface $logger;
    protected ?EventDispatcherInterface     $eventDispatcher;

    /**
     * Kernel constructor.
     *
     * @param RequestHandlerInterface           $requestHandler
     * @param ServerRequestInterface|null       $request
     * @param ResponderInterface|null           $responder
     * @param EventDispatcherInterface|null     $eventDispatcher
     * @param LoggerInterface|null              $logger
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        ? ServerRequestInterface $request = null,
        ? ResponderInterface $responder = null,
        ? EventDispatcherInterface $eventDispatcher = null,
        ? LoggerInterface $logger = null,
    )
    {
        $this->requestHandler       = $requestHandler;
        $this->request              = $request;
        $this->responder            = $responder ?: new Responder();
        $this->logger               = $logger ?: new NullLogger();
        $this->eventDispatcher      = $eventDispatcher;

        if (!$request) {
            $this->request = (new ServerRequestFactory())->createServerRequestFromEnvironment();
        }
    }

    /**
     * Returns the responder that is used to send a response to the client.
     *
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Sets a logger.
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * Uses the request and response to execute the registered
     * request middleware handler and send the response to
     * the client.
     *
     * If the kernel has an event manager then the following event will be fired:
     *  - BeforeHandleRequestEvent
     *  - BeforeSendRequestEvent
     *  - AfterSendRequestEvent
     *  - BeforeTerminateRequestEvent (only if argument 1 is true)
     *
     * @param bool $terminate
     * @param bool $clearUnexpectedBuffer
     *
     * @see Kernel::setEventDispatcher()
     */
    public function execute(bool $terminate = true, bool $clearUnexpectedBuffer = true): void
    {
        $this->logger->debug(
            sprintf('Received request %s %s', $this->request->getMethod(), $this->request->getUri())
        );

        $this?->eventDispatcher->dispatch(new BeforeHandleRequestEvent());

        $response = $this->requestHandler->handle($this->request);

        $this?->eventDispatcher->dispatch(new BeforeSendRequestEvent());

        if ($clearUnexpectedBuffer && ob_get_level() > 0) {
            $buffer = ob_get_clean();

            if( is_string($buffer) && ! empty($buffer) ){
                $this->logger->notice(sprintf(
                    "Unexpected buffer output which is aside from the primary response: \n%s",
                    $buffer
                ));
            }
        }

        $this->responder->send($response, $this->request);

        $this->logger->debug(
            sprintf(
                'Response sent with status %d %s after %d ms',
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                (int) (microtime(true) * 1000) - (int) ($_SERVER['REQUEST_TIME_FLOAT'] * 1000)
            )
        );

        $this?->eventDispatcher->dispatch(new AfterSendRequestEvent());

        if ( $terminate ) {
            $this?->eventDispatcher->dispatch(new BeforeTerminateRequestEvent());
            die(0);
        }
    }
}
