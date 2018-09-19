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

namespace Vection\Contracts\CQRS\Common;

/**
 * Interface IdentifierInterface
 *
 * @package Vection\Contracts\CQRS\Common
 */
interface IdentifierInterface
{
    /**
     * Returns the identifier.
     *
     * @return int|string
     */
    public function getId();

    /**
     * Sets the identifier.
     *
     * @param int|string $id
     */
    public function setId($id): void;

    /**
     * @return int|string
     */
    public function __toString(): string;
}