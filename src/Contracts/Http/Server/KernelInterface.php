<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Http\Server;

use Vection\Contracts\Event\EventManagerInterface;

/**
 * Interface KernelInterface
 *
 * @package Vection\Contracts\Http\Server
 */
interface KernelInterface
{
    /**
     * @param EventManagerInterface $eventManager
     */
    public function setEventManager(EventManagerInterface $eventManager): void;

    /**
     * @param bool $terminate
     */
    public function execute(bool $terminate = true): void;
}