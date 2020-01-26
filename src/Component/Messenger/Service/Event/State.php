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

namespace Vection\Component\Messenger\Service\Event;

use Vection\Contracts\Messenger\Service\Event\StateInterface;

/**
 * Class State
 *
 * @package Vection\Component\Messenger\Service\Event
 *
 * @author  David Lung <vection@davidlung.de>
 */
class State implements StateInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $attributes;

    /**
     * State constructor.
     *
     * @param string $name
     * @param array  $attributes
     */
    public function __construct(string $name, array $attributes = [])
    {
        $this->name       = $name;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function withName(string $name): StateInterface
    {
        return new State($name, $this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function hasAttribute(string $name): bool
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * @inheritDoc
     */
    public function setAttribute(string $name, string $value): void
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute(string $name): ?string
    {
        return ($this->attributes[$name] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
