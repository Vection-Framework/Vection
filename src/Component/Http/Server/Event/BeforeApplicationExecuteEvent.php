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

namespace Vection\Component\Http\Server\Event;

use Vection\Component\Http\Server\Application;

/**
 * Class BeforeApplicationExecuteEvent
 *
 * @package Vection\Component\Http\Server\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class BeforeApplicationExecuteEvent
{
    public function __construct(
        private readonly Application $application
    ){}

    public function getApplication(): Application
    {
        return $this->application;
    }
}
