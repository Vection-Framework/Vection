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

namespace Vection\Component\Messenger\Transport;

use Vection\Contracts\Messenger\MessageBusInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\Transport\ProcessorInterface;

/**
 * Class Processor
 *
 * @package Vection\Component\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
abstract class Processor implements ProcessorInterface
{
    /**
     * @var MessageBusInterface
     */
    protected $messageBus;

    /**
     * CommandProcessor constructor.
     *
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @inheritDoc
     */
    public function process(MessageInterface $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
