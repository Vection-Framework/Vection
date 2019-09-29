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

use InvalidArgumentException;

/**
 * Class Resource
 *
 * @package Vection\Component\Http\Server\Rest
 */
class Resource
{
    public const METHODS = ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'];

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $canonical;

    /**
     * @var string[]
     */
    protected $allowedMethods = [];

    /**
     * @var string[]
     */
    protected $operations = [];

    /**
     * Resource constructor.
     *
     * @param string $canonical
     * @param string $identifier
     * @param array  $allowedMethods
     * @param array  $operations
     */
    public function __construct(string $canonical, string $identifier, array $allowedMethods = [], array $operations = [])
    {
        $this->canonical = trim($canonical, '/');
        $this->identifier = $identifier;
        $this->setAllowedMethods($allowedMethods);
        $this->operations = $operations;
    }

    /**
     *
     * @return string
     */
    public function getCanonical(): string
    {
        return $this->canonical;
    }

    /**
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     *
     * @param array $methods
     */
    public function setAllowedMethods(array $methods): void
    {
        $methods = array_change_key_case($methods);

        foreach( $methods as $method ){
            if( ! in_array($method, self::METHODS, true) ){
                throw new InvalidArgumentException("The given http method '{$methods}' is not an valid method.");
            }
        }

        $this->allowedMethods = $methods;
    }

    /**
     *
     * @return array
     */
    public function getAllowedMethods(): array
    {
        return $this->allowedMethods;
    }

    /**
     *
     * @param array $operations
     */
    public function setOperations(array $operations): void
    {
        $this->operations = $operations;
    }

    /**
     *
     * @return array
     */
    public function getOperations(): array
    {
        return $this->operations;
    }
}