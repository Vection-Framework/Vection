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

namespace Vection\Contracts\MessageBus;

/**
 * Interface IdentifierInterface
 *
 * @package Vection\Contracts\MessageBus
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
     * @return string
     */
    public function __toString(): string;
}
