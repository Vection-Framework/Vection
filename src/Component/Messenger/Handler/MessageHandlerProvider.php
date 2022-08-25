<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Messenger\Handler;

use ReflectionException;
use ReflectionMethod;
use Vection\Component\Messenger\Exception\RuntimeException;
use Vection\Contracts\Messenger\MessageHandlerFactoryInterface;
use Vection\Contracts\Messenger\MessageHandlerProviderInterface;
use Vection\Contracts\Messenger\MessageInterface;

/**
 * Class MessageHandlerMapper
 *
 * @package Vection\Component\Messenger\Service
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessageHandlerProvider implements MessageHandlerProviderInterface
{
    protected array $map;
    protected ?MessageHandlerFactoryInterface $factory;

    /**
     * MessageHandlerMapper constructor.
     *
     * @param array                               $handlerClassNames
     * @param MessageHandlerFactoryInterface|null $factory
     */
    public function __construct(array $handlerClassNames = [], ?MessageHandlerFactoryInterface $factory = null)
    {
        $this->map     = [];
        $this->factory = $factory;

        foreach ($handlerClassNames as $className) {
            $this->registerHandler($className);
        }
    }

    /**
     * @param string $handlerClassName
     */
    public function registerHandler(string $handlerClassName): void
    {
        try {
            $invokeMethod = new ReflectionMethod($handlerClassName, '__invoke');
            $parameters   = $invokeMethod->getParameters();
            $errorMessage = 'Invalid message handler: The __invoke method must has a typed message parameter.';

            if (count($parameters) === 0) {
                throw new RuntimeException($errorMessage);
            }

            $type = $parameters[0]->getType();

            if ( $type === null || ! $type instanceof \ReflectionNamedType) {
                throw new RuntimeException($errorMessage);
            }

            $this->map[$type->getName()] = $handlerClassName;
        }
        catch (ReflectionException $e) {
            throw new RuntimeException('Unable to register message handler class.', 0, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function getHandler(MessageInterface $message): ?callable
    {
        $className = get_class($message->getBody());

        if (isset($this->map[$className])) {
            $handlerClass = $this->map[$className];

            if ($this->factory !== null) {
                return $this->factory->create($handlerClass);
            }

            return new $handlerClass;
        }

        return null;
    }

}
