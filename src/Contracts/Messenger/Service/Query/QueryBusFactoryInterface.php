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

namespace Vection\Contracts\Messenger\Service\Query;

/**
 * Interface QueryBusFactoryInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface QueryBusFactoryInterface
{
    /**
     * Creates a new instance of type EventBusInterface.
     *
     * @return QueryBusInterface
     */
    public function create(): QueryBusInterface;
}