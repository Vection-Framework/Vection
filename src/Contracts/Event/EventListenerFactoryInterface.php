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

namespace Vection\Contracts\Event;

/**
 * Interface EventListenerFactoryInterface
 *
 * @package Vection\Contracts\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EventListenerFactoryInterface
{
    /**
     * @param string $className
     *
     * @return object
     */
    public function create(string $className): object;
}
