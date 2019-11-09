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

namespace Vection\Component\Messenger\Middleware;

use Vection\Component\Messenger\Middleware\Exception\InvalidPayloadException;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\Payload\ValidatablePayloadInterface;

/**
 * Class PayloadValidatorMiddleware
 *
 * @package Vection\Component\Messenger\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class PayloadValidatorMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @inheritDoc
     *
     * @throws InvalidPayloadException
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $payload = $message->getPayload();

        if ($payload instanceof ValidatablePayloadInterface) {
            $chain = $payload->getValidationChain();
            $chain->verify($payload->getValidatableData());

            if ($violations = $chain->getViolations()) {
                throw new InvalidPayloadException($violations);
            }
        }

        return $sequence->next($message);
    }
}
