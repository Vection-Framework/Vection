<?php

declare(strict_types=1);

namespace Vection\Contracts\Messenger;

/**
 * Interface ConditionalHandlerInterface
 *
 * @package Vection\Contracts\Messenger
 * @author  David Lung <david.lung@appsdock.de>
 */
interface ConditionalHandlerInterface
{
    /**
     * Checks the condition, whether the handler can be executed or not. If not, this method
     * must throw an exception.
     */
    public function checkCondition(): void;
}
