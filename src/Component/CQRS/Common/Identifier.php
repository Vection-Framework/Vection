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

namespace Vection\Component\CQRS\Common;

use Vection\Contracts\CQRS\Common\IdentifierInterface;

/**
 * Class Identifier
 *
 * @package Vection\Component\CQRS\Common
 */
class Identifier implements IdentifierInterface
{
    /**
     * This property contains a numeric of string identifier.
     *
     * @var int|string
     */
    protected $id;

    /**
     * AggregateId constructor.
     *
     * @param int|string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the identifier.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the identifier.
     *
     * @param int|string $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|string
     */
    public function __toString(): string
    {
        return (string)$this->id;
    }
}