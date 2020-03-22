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

use InvalidArgumentException;

/**
 * Class Resource
 *
 * @package Vection\Component\Http\Server\Rest
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Resource
{
    /**
     * @var string
     */
    protected $canonical;

    /**
     * @var string|null
     */
    protected $parameter;

    /**
     * @var string[]
     */
    protected $allowedMethods;

    /**
     * @var array
     */
    protected $metadata;

    /**
     * Resource constructor.
     *
     * @param string $canonical
     * @param string $parameter
     * @param array  $allowedMethods
     */
    public function __construct(string $canonical, ?string $parameter = null, array $allowedMethods = [])
    {
        $this->canonical = trim($canonical, '/');
        $this->parameter = $parameter;
        $this->setAllowedMethods($allowedMethods);
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
     * @return string|null
     */
    public function getParameter(): ?string {
        return $this->parameter;
    }

    /**
     *
     * @param array $methods
     */
    public function setAllowedMethods(array $methods): void
    {
        $methods = array_change_key_case($methods);

        foreach ( $methods as $method ) {
            if ( ! in_array($method, ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'PATCH'], true) ) {
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
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     *
     * @param array $metadata
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }
}
