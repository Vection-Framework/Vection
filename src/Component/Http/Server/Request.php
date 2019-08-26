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

use Vection\Component\Http\Psr\ServerRequest;

/**
 * class Request
 * @package Vection\Component\Http\Server
 */
class Request extends ServerRequest
{
    /** @var array */
    protected $pathParts = [];

    /** @var string */
    protected $contextPath = '/';


    /**
     * @param string $name
     *
     * @return string|array|null
     */
    public function getQueryParam(string $name)
    {
        return $this->queryParams[$name] ?? null;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getBodyParam(string $name)
    {
        $body = $this->getParsedBody();

        if( is_array($body) ){
            return $body[$name] ?? null;
        }

        if( is_object($body) ){
            return $body->$name ?? null;
        }

        return null;
    }

    /**
     * @param int $index
     *
     * @return string|null
     */
    public function getPathPart(int $index): ? string
    {
        if( ! $this->pathParts && ($path = $this->uri->getPath()) !== '/' ){
            $this->pathParts = array_values(array_filter(explode('/', $path)));
        }

        return $this->pathParts[$index] ?? null;
    }

    /**
     * @return array
     */
    public function getPathParts(): array
    {
        if( ! $this->pathParts && ($path = $this->uri->getPath()) !== '/' ){
            $this->pathParts = array_values(array_filter(explode('/', $path)));
        }

        return $this->pathParts;
    }

    /**
     * @param string $contextPath
     */
    public function setContextPath(string $contextPath): void
    {
        $this->contextPath = '/' . ltrim($contextPath, '/');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
         $path = $this->uri->getPath();

         if( strpos($path, $this->contextPath) === 0 ){
             $path = substr($path, strlen($this->contextPath));
         }

         return $path;
    }

    /**
     * @return string
     */
    public function getContextPath(): string
    {
        return $this->contextPath;
    }

    /**
     * @return string
     */
    public function getContextPathName(): string
    {
        return substr($this->contextPath, 1);
    }

}