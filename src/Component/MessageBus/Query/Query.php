<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Query;

use Vection\Component\MessageBus\Message;
use Vection\Contracts\MessageBus\PayloadInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class Query
 *
 * @package Vection\Component\MessageBus\Query
 */
abstract class Query extends Message implements QueryInterface, ValidatableInterface
{
    /**
     * Query constructor.
     *
     * @param PayloadInterface $payload
     */
    public function __construct(PayloadInterface $payload = null)
    {
        parent::__construct($payload);
    }

    /**
     * Defines constraints for a set of data.
     *
     * @param ValidationChainInterface $chain
     */
    public function defineValidation(ValidationChainInterface $chain): void {}
}