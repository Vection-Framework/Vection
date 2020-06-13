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

namespace Vection\Component\Messenger\Service\Query;

use Vection\Component\Messenger\Exception\IllegalArgumentException;
use Vection\Component\Messenger\Message;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Component\Messenger\MessageIdGenerator;
use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageIdGeneratorInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Service\Query\QueryBusInterface;
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
     * @var MessageIdGeneratorInterface
     */
    protected $idGenerator;

    /**
     * QueryBus constructor.
     *
     * @param MessageBusInterface              $messageBus
     * @param MessageIdGeneratorInterface|null $idGenerator
     */
    public function __construct(MessageBusInterface $messageBus, MessageIdGeneratorInterface $idGenerator = null)
    {
        $this->messageBus  = $messageBus;
        $this->idGenerator = $idGenerator ?: new MessageIdGenerator();
    }

    /**
     * @inheritDoc
     */
    public function query(object $query): ?ReadModelInterface
    {
        if (! $query instanceof MessageInterface) {
            $query = new Message($query);
        }

        $headers = $query->getHeaders();

        if (! $headers->has(MessageHeaders::MESSAGE_ID)) {
            $query = $query->withHeader(MessageHeaders::MESSAGE_ID, $this->idGenerator->generate());
        }

        if (! $headers->has(MessageHeaders::TIMESTAMP)) {
            $query = $query->withHeader(MessageHeaders::TIMESTAMP, (string) time());
        }

        $query = $query->withHeader(MessageHeaders::MESSAGE_TAG, 'query');

        $message   = $this->messageBus->dispatch($query);
        $readModel = $message->getBody();

        if ($readModel !== null && ! $readModel instanceof ReadModelInterface) {
            throw new IllegalArgumentException(
                'Illegal type as result of a query handler. Excepts an object of type ReadModelInterface but got'.
                (is_object($readModel) ? get_class($readModel) : gettype($readModel))
            );
        }

        return $readModel;
    }
}
