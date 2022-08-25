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

namespace Vection\Component\Messenger;

use Closure;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;

/**
 * Class MiddlewareSequence
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MiddlewareSequence implements MiddlewareSequenceInterface
{
    /**
     * This property contains all middleware objects
     * that will be executed sequentially.
     *
     * @var MessageBusMiddlewareInterface[]
     */
    protected array $middleware = [];

    /**
     * This property contains an closure that returns
     * an middleware object on each call. It uses a generator
     * inside to return always the next middleware.
     *
     * @var Closure
     */
    protected Closure $sequence;

    /**
     * This property contains the middleware that is currently
     * selected to handle the message.
     *
     * @var MessageBusMiddlewareInterface
     */
    protected MessageBusMiddlewareInterface $currentMiddleware;

    /**
     * MiddlewareSequence constructor.
     *
     * @param array $middleware
     */
    public function __construct(array $middleware)
    {
        $this->middleware = $middleware;

        // @var Generator $generator
        $generator = (function(){
            foreach ($this->middleware as $m) {
                yield $m;
            }
        })();

        $this->sequence = static function() use ($generator){
            $current = $generator->current();
            $generator->next();
            return $current;
        };
    }

    /**
     * Returns the current middleware which handles the message.
     *
     * @return MessageBusMiddlewareInterface
     */
    public function getCurrent(): MessageBusMiddlewareInterface
    {
        return $this->currentMiddleware;
    }

    /**
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    public function next(MessageInterface $message): MessageInterface
    {
        $middleware = ($this->sequence)();

        if ($middleware !== null) {
            $this->currentMiddleware = $middleware;
            return $middleware->handle($message, $this);
        }

        return $message;
    }
}
