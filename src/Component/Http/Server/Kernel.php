<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Vection\Component\Http\Psr\Message\Factory\ServerRequestFactory;
use Vection\Component\Http\Server\Decorator\Factory\ServerRequestFactoryDecorator;
use Vection\Contracts\Event\EventManagerInterface;
use Vection\Contracts\Http\Server\KernelInterface;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Kernel
 *
 * @package Vection\Component\Http\Server
 */
class Kernel implements KernelInterface
{

    /** @var ServerRequestInterface */
    protected $request;

    /** @var RequestHandlerInterface */
    protected $requestHandler;

    /** @var ResponderInterface */
    protected $responder;

    /** @var EventManagerInterface */
    protected $eventManager;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * Kernel constructor.
     *
     * @param RequestHandlerInterface            $requestHandler
     * @param ServerRequestInterface             $request
     * @param ResponderInterface|null            $responder
     */
    public function __construct(
        RequestHandlerInterface $requestHandler,
        ? ServerRequestInterface $request = null,
        ? ResponderInterface $responder = null
    )
    {
        $this->requestHandler = $requestHandler;
        $this->request        = $request;
        $this->responder      = $responder ?: new Responder();

        if ( ! $this->request ) {
            $serverRequestFactory = new ServerRequestFactoryDecorator(new ServerRequestFactory());
            $this->request        = $serverRequestFactory->createFromGlobals();
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
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager): void
    {
        $this->eventManager = $eventManager;
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
     * @param string $event
     */
    protected function fireEvent(string $event): void
    {
        if ( $this->eventManager ) {
            $this->eventManager->fire('vection.http.kernel.'.$event);
        }
    }

    /**
     * @param string $message
     */
    protected function log(string $message): void
    {
        if ( $this->logger ) {
            $this->logger->info($message);
        }
    }

    /**
     * Uses the request and response to execute the registered
     * request middleware handler and send the response to
     * the client.
     *
     * If the kernel has an event manager then the following event will be fired:
     *  - vection.http.kernel.beforeHandleRequest
     *  - vection.http.kernel.beforeSendRequest
     *  - vection.http.kernel.afterSendRequest
     *  - vection.http.kernel.beforeTerminate (only if argument 1 is true)
     *
     * @see Kernel::setEventManager()
     *
     * @param bool $terminate
     */
    public function execute(bool $terminate = true): void
    {
        $this->fireEvent('beforeHandleRequest');

        $this->log('Vection HTTP: Kernel start handle request by middleware');
        $response = $this->requestHandler->handle($this->request);
        $this->log('Vection HTTP: Kernel finished request handling');

        $this->fireEvent('beforeSendRequest');

        $this->log('Vection HTTP: Kernel.Responder start processing');
        $this->responder->send($response, $this->request);
        $this->log('Vection HTTP: Kernel.Responder request sent');

        $this->fireEvent('afterSendRequest');

        if ( $terminate ) {
            $this->log('Vection HTTP: Kernel start termination');
            $this->fireEvent('beforeTerminate');
            $this->log('Vection HTTP: Kernel TERMINATED');
            die(0);
        }

        $this->log('Vection HTTP: Kernel finished execution without termination');
    }
}
