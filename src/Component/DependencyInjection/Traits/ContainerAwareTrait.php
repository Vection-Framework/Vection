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

namespace Vection\Component\DependencyInjection\Traits;

use Vection\Component\DependencyInjection\Container;

/**
 * Trait ContainerAwareTrait
 *
 * @package Vection\Component\DependencyInjection\Traits
 */
trait ContainerAwareTrait
{

    /** @var Container */
    protected Container $container;

    /**
     * @param Container $container
     */
    public function __setContainer(Container $container): void
    {
        $this->container = $container;
    }

    /**
     * @return Container
     */
    protected function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param string $identifier
     * @param array  $constructParams
     * @param bool   $shared
     *
     * @return object
     */
    protected function new(string $identifier, array $constructParams = [], bool $shared = true): object
    {
        return $this->container->create($identifier, $constructParams, $shared);
    }
}
