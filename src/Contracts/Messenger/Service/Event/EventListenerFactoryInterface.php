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

namespace Vection\Contracts\Messenger\Service\Event;

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface EventListenerFactoryInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface EventListenerFactoryInterface
{
    /**
     * @param string           $className
     * @param MessageInterface $message
     *
     * @return object
     */
    public function createListener(string $className, MessageInterface $message): object;
}
