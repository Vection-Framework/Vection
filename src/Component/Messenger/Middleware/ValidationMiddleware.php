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

use Vection\Component\Messenger\Exception\ValidationFailedException;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MessageInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;
use Vection\Contracts\Messenger\ValidatableInterface;

/**
 * Class PayloadValidatorMiddleware
 *
 * @package Vection\Component\Messenger\Middleware
 *
 * @author  David Lung <vection@davidlung.de>
 */
class ValidationMiddleware implements MessageBusMiddlewareInterface
{
    /**
     * @inheritDoc
     *
     * @throws ValidationFailedException
     */
    public function handle(MessageInterface $message, MiddlewareSequenceInterface $sequence): MessageInterface
    {
        $body = $message->getBody();

        if ($body instanceof ValidatableInterface) {
            $chain = $body->getValidationChain();
            $chain->verify($body->getValidatableData());

            if ($violations = $chain->getViolations()) {
                throw new ValidationFailedException($violations);
            }
        }

        return $sequence->next($message);
    }
}
