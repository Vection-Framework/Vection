<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
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
use Vection\Component\Http\Server\Rest\Exception\BadRequestException;
use Vection\Component\Http\Server\Rest\Exception\ResourceNotFoundException;
use Vection\Component\Http\Server\Rest\Resource as RestResource;
use Vection\Contracts\Cache\CacheAwareInterface;
use Vection\Contracts\Cache\CacheInterface;

/**
 * Class Resolver
 *
 * @package Vection\Component\Http\Server\Rest
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
     * @param RestResource $resource
     */
    public function add(RestResource $resource): void
    {
        $this->resources[] = $resource;
        $this->methodOperations = [
            'GET' => 'get', 'POST' => 'create', 'PUT' => 'update', 'DELETE' => 'delete'
        ];
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
        if( ! isset($this->methodOperations[$method = strtoupper($method)]) ){
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
     * @throws BadRequestException
     * @throws ResourceNotFoundException
     */
    public function match(string $method, string $path): Match
    {
        $method = strtoupper($method);

        if( $this->cache && $this->cache->contains($path) ){
            /** @var Match $match */
            $match = $this->cache->getObject($path);
            return $match;
        }

        $tree = [];

        # Build routing tree
        foreach( $this->resources as $resource ){
            $temp = &$tree;
            foreach( explode('/', $resource->getCanonical()) as $seg) {
                $temp = &$temp[$seg];
            }
            $temp['#resource'] = $resource;
        }

        $resource = null;
        $resourceIds = [];
        $rootResource = null;
        $methodOperations = $this->methodOperations;
        $segments = explode('/', $path);

        while( $segment = array_shift($segments) ){

            $operation = null;

            if( strpos($segment, ':') ){
                [$segment, $operation] = explode(':', $segment);
            }

            if( ! isset($tree[$segment]) ){
                throw new ResourceNotFoundException('The requested resource does not exists.');
            }

            $tree = $tree[$segment];

            /** @var RestResource $resource */
            $resource = $tree['#resource'];
            $resourceId = null;

            if( $rootResource === null ){
                $rootResource = $resource;
            }

            $allowedMethods = $resource->getAllowedMethods();
            if( ! in_array($method, $allowedMethods, true) ){
                throw new BadRequestException(
                    'Invalid method. This resource supports only one of this methods: '.implode(', ', $allowedMethods)
                );
            }

            foreach( $this->guards as $guard ){
                $guard($resource);
            }

            if( $resource instanceof Collection){
                $resourceId = array_shift($segments);
                if( $resourceId === null ){
                    if( $operation ){
                        $methodOperations['POST'] = lcfirst($operation);
                    }else{
                        $methodOperations['GET'] = 'list';
                    }
                    break;
                }
                $resourceIds[] = $resourceId;
            }
            elseif($operation){
                $methodOperations['POST'] = lcfirst($operation);
            }

            if( $operation && count($segments) > 0 ){
                throw new BadRequestException(
                    'Alternative resource operations cannot contain further child resources.'
                );
            }
        }

        if( $methodOperations['POST'] !== 'create' ){
            if( $method !== 'POST' ){
                throw new BadRequestException(
                    'Alternative resource operations must be requested by POST.'
                );
            }
            if( ! preg_match('/^[a-zA-Z]+$/', $methodOperations['POST']) ){
                throw new BadRequestException(
                    'Invalid operation method.'
                );
            }
        }

        $match = new Match($resource, $methodOperations[$method], $resourceIds);
        $this->cache && $this->cache->setObject($path, $match);
        return $match;
    }
}