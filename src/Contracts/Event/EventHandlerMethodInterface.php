<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\Event;

/**
 * Interface EventHandlerMethodInterface
 *
 * @package Vection\Contracts\Event
 */
interface EventHandlerMethodInterface
{
    /**
     * Returns the name of the method which handles the event.
     *
     * @return string
     */
    public function getHandlerMethodName(): string;
}