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

namespace Vection\Component\Messenger\Service;

use Vection\Contracts\Messenger\MessageRelationInterface;
use Vection\Contracts\Messenger\Service\Command\CommandBusFactoryInterface;
use Vection\Contracts\Messenger\Service\Command\CommandBusInterface;
use Vection\Contracts\Messenger\Service\Event\EventBusFactoryInterface;
use Vection\Contracts\Messenger\Service\Event\EventBusInterface;
use Vection\Contracts\Messenger\Service\Query\QueryBusFactoryInterface;
use Vection\Contracts\Messenger\Service\Query\QueryBusInterface;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;
use Vection\Contracts\Messenger\Service\ServiceBusInterface;

/**
 * Class ServiceBus
 *
 * @package Vection\Component\Messenger\Service
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ServiceBus implements ServiceBusInterface
{
    /**
     * @var CommandBusFactoryInterface
     */
    protected $commandBusFactory;

    /**
     * @var CommandBusInterface
     */
    protected $commandBus;

    /**
     * @var EventBusFactoryInterface
     */
    protected $eventBusFactory;

    /**
     * @var EventBusInterface
     */
    protected $eventBus;

    /**
     * @var QueryBusFactoryInterface
     */
    protected $queryBusFactory;

    /**
     * @var QueryBusInterface
     */
    protected $queryBus;

    /**
     * @var bool
     */
    protected $lazyInitialization;

    /**
     * ServiceBus constructor.
     *
     * @param CommandBusFactoryInterface $commandBusFactory
     * @param EventBusFactoryInterface   $eventBusFactory
     * @param QueryBusFactoryInterface   $queryBusFactory
     * @param bool                       $lazyInitialization
     */
    public function __construct(
        CommandBusFactoryInterface $commandBusFactory,
        EventBusFactoryInterface $eventBusFactory,
        QueryBusFactoryInterface $queryBusFactory,
        bool $lazyInitialization = true)
    {
        $this->commandBusFactory = $commandBusFactory;
        $this->eventBusFactory = $eventBusFactory;
        $this->queryBusFactory = $queryBusFactory;
        $this->lazyInitialization = $lazyInitialization;

        if (!$lazyInitialization) {
            $this->commandBus = $commandBusFactory->create();
            $this->eventBus = $eventBusFactory->create();
            $this->queryBus = $queryBusFactory->create();
        }
    }

    /**
     * @inheritDoc
     */
    public function execute(object $command, ?MessageRelationInterface $relation = null): void
    {
        if ($this->commandBus === null) {
            $this->commandBus = $this->commandBusFactory->create();
        }

        $this->commandBus->execute($command, $relation);
    }

    /**
     * @inheritDoc
     */
    public function publish(object $event, ?MessageRelationInterface $relation = null): void
    {
        if ($this->eventBus === null) {
            $this->eventBus = $this->eventBusFactory->create();
        }

        $this->eventBus->publish($event, $relation);
    }

    /**
     * @inheritDoc
     */
    public function query(object $query): ?ReadModelInterface
    {
        if ($this->queryBus === null) {
            $this->queryBus = $this->queryBusFactory->create();
        }

        return $this->queryBus->query($query);
    }

}