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

namespace Vection\Component\Http\Server\Rest;

use Closure;
use InvalidArgumentException;
use Vection\Component\Http\Exception\HttpBadRequestException;
use Vection\Component\Http\Exception\HttpMethodNotAllowedException;
use Vection\Component\Http\Exception\HttpNotFoundException;
use Vection\Component\Http\Server\Rest\Resource as RestResource;
use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class Resolver
 *
 * @package Vection\Component\Http\Server\Rest
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Resolver implements CacheAwareInterface
{

    /**
     * @var RestResource[]
     */
    protected $resources;

    /**
     * @var string[]
     */
    protected $methodOperations;

    /**
     * @var Closure[]
     */
    protected $guards;

    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Resolver constructor.
     */
    public function __construct()
    {
        $this->methodOperations = [
            'GET' => 'get', 'POST' => 'create', 'PUT' => 'update', 'DELETE' => 'delete', 'PATCH' => 'modify',
            'OPTIONS' => 'options', 'HEAD' => 'head'
        ];
    }

    /**
     * @param RestResource $resource
     */
    public function add(RestResource $resource): void
    {
        $this->resources[] = $resource;
    }

    /**
     * @param CacheInterface $cache
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache->getPool('Vection.Http.Rest');
    }

    /**
     * @param array $methodOperations
     */
    public function setMethodOperations(array $methodOperations): void
    {
        $this->methodOperations = array_change_key_case($methodOperations, CASE_UPPER);
    }

    /**
     * @param string $method
     * @param string $operation
     */
    public function setMethodOperation(string $method, string $operation): void
    {
        if ( ! isset($this->methodOperations[$method = strtoupper($method)]) ) {
            throw new InvalidArgumentException("The method '{$method}' is not a valid http method.");
        }

        $this->methodOperations[$method] = $operation;
    }

    /**
     * @param Closure $callback
     */
    public function addResourceGuard(Closure $callback): void
    {
        $this->guards[] = $callback;
    }

    /**
     * @param string $method
     * @param string $path
     *
     * @return Match
     *
     * @throws HttpBadRequestException
     * @throws HttpNotFoundException
     * @throws HttpMethodNotAllowedException
     */
    public function match(string $method, string $path): Match
    {
        $method = strtoupper($method);

        if ( $this->cache && $this->cache->contains($method.$path) ) {
            /** @var Match $match */
            $match = $this->cache->getObject($method.$path);
            return $match;
        }

        $tree = [];

        # Build routing tree
        foreach ( $this->resources as $resource ) {
            $temp = &$tree;
            foreach ( explode('/', $resource->getCanonical()) as $seg) {
                $temp = &$temp[$seg];
            }
            $temp['#resource'] = $resource;
        }

        $resource         = null;
        $resourceIds      = [];
        $rootResource     = null;
        $methodOperations = $this->methodOperations;
        $segments         = explode('/', $path);

        while ( $segment = array_shift($segments) ) {

            $operation = null;

            if ( strpos($segment, ':') ) {
                [$segment, $operation] = explode(':', $segment);
            }

            if ( ! isset($tree[$segment]) ) {
                throw new HttpNotFoundException('The requested resource does not exists.');
            }

            $tree = $tree[$segment];

            if (! isset($tree['#resource'])) {
                throw new HttpNotFoundException('The requested resource does not exists.');
            }

            /** @var RestResource $resource */
            $resource   = $tree['#resource'];
            $resourceId = null;

            if ( $rootResource === null ) {
                $rootResource = $resource;
            }

            foreach ( $this->guards as $guard ) {
                $guard($resource);
            }

            if ( $resource->getParameter()) {

                $resourceId = array_shift($segments);

                if ( $operation ) {
                    $methodOperations['POST'] = lcfirst($operation);
                } else if ($resourceId === null) {
                    $methodOperations['GET'] = 'list';
                }

                if ( $resourceId === null ) {
                    break;
                }

                $resourceIds[] = $resourceId;
            } else if ($operation) {
                $methodOperations['POST'] = lcfirst($operation);
            }

            if ( $operation && count($segments) > 0 ) {
                throw new HttpBadRequestException(
                    'Alternative resource operations cannot contain further child resources.'
                );
            }
        }

        if ( $methodOperations['POST'] !== 'create' ) {
            if ( $method !== 'POST' ) {
                throw new HttpBadRequestException(
                    'Alternative resource operations must be requested by POST.'
                );
            }
            if ( ! preg_match('/^[a-zA-Z_-]+$/', $methodOperations['POST']) ) {
                throw new HttpBadRequestException('Invalid operation method.');
            }
        }

        $allowedMethods = array_merge($resource->getAllowedMethods(), ['OPTIONS', 'HEAD']);

        if ( ! in_array($method, $allowedMethods, true) ) {
            throw new HttpMethodNotAllowedException(
                'Invalid method. This resource supports only one of this methods: '.implode(', ', $allowedMethods)
            );
        }

        $match = new Match($resource, $methodOperations[$method], $resourceIds);
        $this->cache && $this->cache->setObject($method.$path, $match);
        return $match;
    }
}
