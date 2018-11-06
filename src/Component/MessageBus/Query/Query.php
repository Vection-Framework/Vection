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

use Vection\Component\MessageBus\Identifier;
use Vection\Component\MessageBus\Message;
use Vection\Contracts\MessageBus\IdentifierInterface;
use Vection\Contracts\MessageBus\PayloadInterface;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\Validator\ValidatableInterface;

/**
 * Class Query
 *
 * @package Vection\Component\MessageBus\Query
 */
abstract class Query extends Message implements QueryInterface, ValidatableInterface
{
    /**
     * An instance of Identifier that can contains the
     * identifier for the requested resource.
     *
     * @var IdentifierInterface
     */
    protected $identifier;

    /**
     * Query constructor.
     *
     * @param int|string|null  $identifier
     * @param PayloadInterface $payload
     */
    public function __construct($identifier = null, PayloadInterface $payload = null)
    {
        parent::__construct($payload);

        $this->identifier = new Identifier($identifier);
    }

    /**
     * @inheritDoc
     */
    public function getIdentity(): IdentifierInterface
    {
        return $this->identifier;
    }
}