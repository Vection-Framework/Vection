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

namespace Vection\Contracts\Messenger\Transport;

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface ReceiverInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface ReceiverInterface
{
    /**
     * @param array $options
     */
    public function setup(array $options = []): void;

    /**
     * @return MessageInterface|null
     */
    public function next(): ?MessageInterface;

    /**
     * @param MessageInterface $message
     */
    public function ack(MessageInterface $message): void;

    /**
     * @param MessageInterface $message
     */
    public function reject(MessageInterface $message): void;
}
