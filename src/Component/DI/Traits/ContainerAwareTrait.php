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

namespace Vection\Component\DI\Traits;

use Vection\Component\DI\Container;

/**
 * Trait ContainerAwareTrait
 *
 * @package Vection\Component\DI\Traits
 */
trait ContainerAwareTrait
{

    /** @var Container */
    protected $container;

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
}
