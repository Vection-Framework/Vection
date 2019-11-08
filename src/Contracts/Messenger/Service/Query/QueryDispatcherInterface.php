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

use RuntimeException;

/**
 * Interface QueryDispatcherInterface
 *
 * @package Vection\Contracts\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface QueryDispatcherInterface
{
    /**
     * Dispatch and executes the query related handler and returns
     * an object of type ReadModelInterface or null if no data was found.
     *
     * @param QueryInterface $query
     *
     * @return ReadModelInterface
     *
     * @throws RuntimeException If the query has no related handler.
     */
    public function dispatch(QueryInterface $query): ?ReadModelInterface;
}
