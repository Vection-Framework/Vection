<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Query\Middleware;

use Vection\Contracts\Cache\CacheInterface;
use Vection\Contracts\MessageBus\Query\QueryBusMiddlewareInterface;
use Vection\Contracts\MessageBus\Query\QueryBusSequenceInterface;
use Vection\Contracts\MessageBus\Query\QueryCacheHandlerInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\QueryResolverInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class QueryDispatcherBus
 *
 * @package Vection\Component\MessageBus\Query\Middleware
 */
class QueryDispatcherBus implements QueryBusMiddlewareInterface
{
    /** @var QueryResolverInterface */
    protected $resolver;

    /** @var CacheInterface */
    protected $cache;

    /**
     * DispatcherQueryBus constructor.
     *
     * @param QueryResolverInterface $resolver
     * @param CacheInterface|null    $cache
     */
    public function __construct(QueryResolverInterface $resolver, CacheInterface $cache = null)
    {
        $this->resolver = $resolver;
        $this->cache = $cache;
    }

    /**
     * @param QueryInterface            $message
     * @param QueryBusSequenceInterface $sequence
     *
     * @return ReadModelInterface|null
     */
    public function __invoke(QueryInterface $message, QueryBusSequenceInterface $sequence)
    {
        /** @var callable $handler */
        $handler = $this->resolver->resolve($message);

        # Check if this Middleware uses cache for query models and handler supports caching
        if( $this->cache && $handler instanceof QueryCacheHandlerInterface){

            # Each handler have an own cache pools for all payload variants
            $pool = $this->cache->getPool(get_class($handler));
            $cacheKey = $message->payload()->getFingerprint();

            if( $pool->contains($cacheKey) ){
                /** @var ReadModelInterface $readModel */
                $readModel = $pool->getObject($cacheKey);
                return $readModel;
            }

            $readModel = $handler($message);
            $pool->setObject($cacheKey, $readModel);

            return $readModel;
        }

        return $handler($message);
    }
}