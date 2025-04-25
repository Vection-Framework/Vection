<?php

declare(strict_types=1);

namespace Vection\Component\Http\Server;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;
use ReflectionException;
use Vection\Component\Common\Exception\RuntimeException;
use Vection\Component\Http\Psr\Server\RequestHandler;
use Vection\Component\Http\Server\Event\BeforeApplicationExecuteEvent;
use Vection\Component\Http\Server\Message\Factory\ServerRequestFactory;
use Vection\Component\Http\Server\Message\ServerRequest;
use Vection\Contracts\Http\Server\ResponderInterface;

/**
 * Class Application
 *
 * @package Vection\Component\Http\Server
 * @author  David Lung <david.lung@appsdock.de>
 */
class Application
{
    protected ServerRequest|ServerRequestInterface $request;
    protected ResponderInterface $responder;
    protected EventDispatcherInterface|null $eventDispatcher;
    protected LoggerInterface $logger;
    protected ContainerInterface|null $container;

    /** @var array<int, string|MiddlewareInterface> */
    protected array $middlewares;

    /**
     * Application constructor.
     */
    public function __construct(Environment|null $environment = null)
    {
        $factory = new ServerRequestFactory();
        $this->request = $factory->createServerRequestFromEnvironment($environment ?: new Environment());
        $this->responder = new Responder();
        $this->logger = new NullLogger();
        $this->eventDispatcher = null;
        $this->container = null;
    }

    /**
     * @param ContainerInterface $container
     *
     * @return static
     */
    public function setContainer(ContainerInterface $container): static
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ServerRequest|ServerRequestInterface $request
     *
     * @return static
     */
    public function setServerRequest(ServerRequest|ServerRequestInterface $request): static
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ServerRequestInterface|ServerRequest
     */
    public function getServerRequest(): ServerRequestInterface|ServerRequest
    {
        return $this->request;
    }

    /**
     * @param ResponderInterface $responder
     *
     * @return static
     */
    public function setResponder(ResponderInterface $responder): static
    {
        $this->responder = $responder;
        return $this;
    }

    /**
     * @return ResponderInterface
     */
    public function getResponder(): ResponderInterface
    {
        return $this->responder;
    }

    /**
     * @param LoggerInterface $logger
     *
     * @return static
     */
    public function setLogger(LoggerInterface $logger): static
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param EventDispatcherInterface $eventDispatcher
     *
     * @return static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): static
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * @return EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @param string|MiddlewareInterface $middleware
     *
     * @return $this
     */
    public function addMiddleware(string|MiddlewareInterface $middleware): static
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * @param array<int, string|MiddlewareInterface> $middlewares
     *
     * @return $this
     */
    public function setMiddlewares(array $middlewares): static
    {
        foreach ($middlewares as $middleware) {
            $this->addMiddleware($middleware);
        }

        return $this;
    }

    /**
     * @param bool $terminate
     * @param bool $clearUnexpectedBuffer
     */
    public function execute(bool $terminate = true, bool $clearUnexpectedBuffer = true): void
    {
        $this->eventDispatcher?->dispatch(new BeforeApplicationExecuteEvent($this));

        $requestHandler = new RequestHandler();

        foreach ($this->middlewares as $middleware) {

            if (is_string($middleware)) {
                if ($this->container !== null) {
                    $middleware = $this->container->get($middleware);
                }
                else {
                    try {
                        $middleware = (new ReflectionClass($middleware))->newInstance();
                    }
                    catch (ReflectionException $e) {
                        throw new RuntimeException(
                            "Unable to setup the request handler because of " .
                            "missing or invalid middleware ($middleware)",
                            previous: $e
                        );
                    }
                }

                if (!$middleware instanceof MiddlewareInterface) {
                    throw new RuntimeException(
                        "Unable to setup the request handler because " .
                        "middleware $middleware is not from type ".MiddlewareInterface::class,
                    );
                }
            }

            $requestHandler->addMiddleware($middleware);
        }

        $kernel = new Kernel($requestHandler, $this->request, $this->responder);
        $kernel->setLogger($this->logger);

        if ($this->eventDispatcher) {
            $kernel->setEventDispatcher($this->eventDispatcher);
        }

        $kernel->execute($terminate, $clearUnexpectedBuffer);
    }
}
