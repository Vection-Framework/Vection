<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Messenger\Exception;

use Throwable;
use Vection\Component\Messenger\MessageHeaders;
use Vection\Contracts\Messenger\MessageInterface;

/**
 * Class HandlingFailedException
 *
 * @package Vection\Component\Messenger\Exception
 *
 * @author  David Lung <vection@davidlung.de>
 */
class HandlerFailedException extends RuntimeException
{
    protected MessageInterface $envelope;

    /**
     * HandlerFailedException constructor.
     *
     * @param Throwable|null $exception
     */
    public function __construct(MessageInterface $envelope, Throwable $exception)
    {
        $this->envelope = $envelope;
        $body = $envelope->getBody();

        $envelope = sprintf(
            'Handling for message "%s" with body "%s" failed.',
            $envelope->getHeaders()->get(MessageHeaders::MESSAGE_ID),
            is_object($body) ? str_replace('\\', '.', get_class($body)) : gettype($body),
        );

        parent::__construct($envelope, $exception->getCode(), $exception);
    }

    /**
     * @return MessageInterface
     */
    public function getEnvelope(): MessageInterface
    {
        return $this->envelope;
    }
}
