<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\CQRS\Common;

/**
 * Class BusSequence
 *
 * @package Vection\Contracts\CQRS\Common
 */
abstract class BusSequence
{
    /**
     * This property contains all middleware objects
     * that will be executed sequentially.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * This property contains an closure that returns
     * an middleware object on each call. It uses a generator
     * inside to return always the next middleware.
     * 
     * @var \Closure
     */
    protected $relay;

    /**
     * This property contains the counter for sequence 
     * invocations. This is used to determine if there 
     * exists a next middleware object.
     *  
     * @var int 
     */
    protected $pointer = 0;

    /**
     * Invokes the next middleware of this sequence.
     *
     * @param MessageInterface $message
     *
     * @return mixed|null
     */
    abstract public function invokeNext(MessageInterface $message);

    /**
     * Sequence constructor.
     * 
     * @param array $middleware
     */
    public function __construct(array $middleware)
    {
        $this->middleware = $middleware;

        /** @var \Generator $generator */
        $generator = (function() {
            foreach( $this->middleware as $m) yield $m;
        })();

        $this->relay = function() use ($generator){
            $current = $generator->current();
            $generator->next();
            $this->pointer++;
            return $current;
        };
    }

    /**
     * Checks if there is a next middleware object.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->pointer < count($this->middleware);
    }

}