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

declare(strict_types = 1);

namespace Vection\Contracts\Messenger\Service\Event;

/**
 * Interface StateInterface
 *
 * @package Vection\Contracts\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface StateInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return StateInterface
     */
    public function withName(string $name): StateInterface;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasAttribute(string $name): bool;

    /**
     * @param string $name
     * @param string $value
     */
    public function setAttribute(string $name, string $value): void;

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute(string $name): ?string;

    /**
     * @return array
     */
    public function getAttributes(): array;
}
