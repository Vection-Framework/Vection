<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Contracts\Messenger;

/**
 * Interface MessageHandlerFactoryInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageHandlerFactoryInterface
{
    /**
     * @param string $className
     *
     * @return callable
     */
    public function create(string $className): callable;
}
