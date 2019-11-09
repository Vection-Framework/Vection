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

namespace Vection\Component\Messenger\Service\Query;

use RuntimeException;
use Vection\Contracts\Messenger\Service\Query\QueryDispatcherInterface;
use Vection\Contracts\Messenger\Service\Query\QueryInterface;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;

/**
 * Class QueryDispatcher
 *
 * @package Vection\Component\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
class QueryDispatcher implements QueryDispatcherInterface
{
    /**
     * @var callable[]
     */
    protected $handlers;

    /**
     * @param string   $queryClassName
     * @param callable $handler
     */
    public function register(string $queryClassName, callable $handler): void
    {
        $this->handlers[$queryClassName] = $handler;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(QueryInterface $query): ?ReadModelInterface
    {
        $queryClassName = get_class($query);

        if (! isset($this->handlers[$queryClassName])) {
            throw new RuntimeException('Query has no related handler.');
        }

        return $this->handlers[$queryClassName]($query);
    }
}
