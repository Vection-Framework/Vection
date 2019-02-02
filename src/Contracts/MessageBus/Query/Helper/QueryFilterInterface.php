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

namespace Vection\Contracts\MessageBus\Query\Helper;

/**
 * Interface QueryFilterInterface
 *
 * @package Vection\Contracts\MessageBus\Query\Helper
 */
interface QueryFilterInterface
{
    /**
     * @param string $filter
     */
    public function apply(string $filter): void;

    /**
     * Returns one filter values by its name.
     *
     * @param string $name
     *
     * @return string
     */
    public function getValue(string $name): ?string;

    /**
     * Returns all filter values as assoc array with name as key.
     *
     * @return array
     */
    public function getValues(): array;

    /**
     * Returns the applied filter as string.
     *
     * @return string
     */
    public function __toString(): string;
}
