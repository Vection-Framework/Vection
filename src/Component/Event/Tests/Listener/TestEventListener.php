<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Event\Tests\Listener;

use Vection\Component\Event\Tests\Event\TestEvent;

/**
 * Class TestEventListener
 *
 * @package Vection\Component\Event\Tests\Listener
 *
 * @author  David Lung <vection@davidlung.de>
 */
class TestEventListener
{
    /**
     * @param TestEvent $event
     */
    public function __invoke(TestEvent $event): void
    {
        define('TEST__EVENT_DISPATCHER_DISPATCH', true);
    }
}
