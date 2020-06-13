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

namespace Vection\Contracts\Messenger\Service;

use Vection\Contracts\Messenger\Service\Command\CommandBusInterface;
use Vection\Contracts\Messenger\Service\Event\EventBusInterface;
use Vection\Contracts\Messenger\Service\Query\QueryBusInterface;

/**
 * Interface ServiceBusInterface
 *
 * @package Vection\Contracts\Messenger\Service
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface ServiceBusInterface extends CommandBusInterface, EventBusInterface, QueryBusInterface
{
    # Aggregation of all service buses
}