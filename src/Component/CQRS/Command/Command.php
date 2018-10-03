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

namespace Vection\Component\CQRS\Command;

use Vection\Contracts\CQRS\Command\CommandInterface;
use Vection\Contracts\CQRS\Common\IdentifierInterface;
use Vection\Contracts\CQRS\Common\PayloadInterface;
use Vection\Contracts\Validator\ValidatableInterface;
use Vection\Component\CQRS\Common\Identifier;
use Vection\Component\CQRS\Common\Message;
use Vection\Component\CQRS\Common\Payload;

/**
 * Class Command
 *
 * @package Vection\Component\CQRS\Command
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
}