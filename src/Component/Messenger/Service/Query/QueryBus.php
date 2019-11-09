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

use InvalidArgumentException;
use Vection\Component\Messenger\MessageBuilder;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\Service\Query\QueryBusInterface;
use Vection\Contracts\Messenger\Service\Query\QueryInterface;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;

/**
 * Class QueryBus
 *
 * @package Vection\Component\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
class QueryBus implements QueryBusInterface
{
    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * QueryBus constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param QueryInterface $query
     *
     * @return ReadModelInterface|null
     */
    public function query(QueryInterface $query): ?ReadModelInterface
    {
        $message = (new MessageBuilder())->withPayload($query)->build();

        $readModel = $this->messageBus->dispatch($message)->getPayload();

        if ($readModel !== null && ! $readModel instanceof ReadModelInterface) {
            throw new InvalidArgumentException('Except a message payload of type ReadModelInterface.');
        }

        return $readModel;
    }
}
