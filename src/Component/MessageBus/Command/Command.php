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

namespace Vection\Component\MessageBus\Command;

use Vection\Contracts\MessageBus\Command\CommandInterface;
use Vection\Contracts\MessageBus\IdentifierInterface;
use Vection\Contracts\MessageBus\PayloadInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Component\MessageBus\Identifier;
use Vection\Component\MessageBus\Message;
use Vection\Component\MessageBus\Payload;
use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class Command
 *
 * @package Vection\Component\MessageBus\Command
 */
abstract class Command extends Message implements CommandInterface, ValidatableInterface
{
    /**
     * An instance of Identifier that can contains the
     * identifier for the requested resource.
     *
     * @var IdentifierInterface
     */
    protected $identifier;

    /**
     * This property determines whether this command
     * should be send by a transactional bus.
     *
     * @var bool
     */
    protected $transactional = false;

    /**
     * Command constructor.
     *
     * @param int|string|null  $identifier
     * @param PayloadInterface $payload
     */
    public function __construct(PayloadInterface $payload = null, $identifier = null)
    {
        parent::__construct($payload);

        $this->identifier = new Identifier($identifier);
        $this->payload = $payload ?: new Payload();
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentifierInterface
    {
        return $this->identifier;
    }

    /**
     * @inheritdoc
     */
    public function isTransactional(): bool
    {
        return $this->transactional;
    }

    /**
     * Defines constraints for a set of data.
     *
     * @param ValidationChainInterface $chain
     */
    public function defineValidation(ValidationChainInterface $chain): void {}
}