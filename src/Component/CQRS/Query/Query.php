<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\CQRS\Query;

use Vection\Component\CQRS\Common\Identifier;
use Vection\Component\CQRS\Common\Message;
use Vection\Contracts\CQRS\Common\IdentifierInterface;
use Vection\Contracts\CQRS\Common\PayloadInterface;
use Vection\Contracts\CQRS\Query\QueryInterface;
use Vection\Contracts\Validator\ValidatableInterface;

/**
 * Class Query
 *
 * @package Vection\Component\CQRS\Query
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
     * @param int|string|null $identifier
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