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
     * @param object $query
     *
     * @return ReadModelInterface|null
     *
     * @see QueryHandlerMiddleware
     */
    public function query(object $query): ?ReadModelInterface;
}
