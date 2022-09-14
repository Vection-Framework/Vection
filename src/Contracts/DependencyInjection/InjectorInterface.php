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

namespace Vection\Contracts\DependencyInjection;

/**
 * Interface InjectorInterface
 *
 * @package Vection\Contracts\DependencyInjection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface InjectorInterface
{
    /**
     * Injects all dependencies defined by the given object.
     *
     * @param object $object
     */
    public function injectDependencies(object $object): void;
}
