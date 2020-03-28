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

use Vection\Component\Http\Server\Rest\Resource as RestResource;

/**
 * Class Match
 *
 * @package Vection\Component\Http\Server\Rest
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class Match
{

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var array
     */
    protected $resourceIds;

    /**
     * Match constructor.
     *
     * @param RestResource $resource
     * @param string   $operation
     * @param array    $resourceIds
     */
    public function __construct(RestResource $resource, string $operation, array $resourceIds)
    {
        $this->resource    = $resource;
        $this->operation   = $operation;
        $this->resourceIds = $resourceIds;
    }

    /**
     * @return RestResource
     */
    public function getResource(): RestResource
    {
        return $this->resource;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return array
     */
    public function getResourceIds(): array
    {
        return $this->resourceIds;
    }

}
