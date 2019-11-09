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

namespace Vection\Contracts\Messenger\Service\Query;

use Vection\Component\Message\Service\Query\Middleware\QueryDispatcherMiddleware;

/**
 * Interface QueryBusInterface
 *
 * @package Vection\Contracts\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface QueryBusInterface
{
    /**
     * Pass the query to the message bus and returns the result.
     * This method provides only a wrapped dispatching of the
     * message bus by creating a message with the given query as
     * payload and pass to the message bus instance. So make sure
     * the message bus has the correct setup and contains a related
     * query dispatcher middleware.
     *
     * @param QueryInterface $query
     *
     * @return ReadModelInterface|null
     * @see QueryDispatcherMiddleware
     *
     */
    public function query(QueryInterface $query): ?ReadModelInterface;
}
