<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Contracts\Common\Collection;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;

/**
 * Interface CollectionInterface
 *
 * @package Vection\Contracts\Common\Collection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface CollectionInterface extends Countable, ArrayAccess, Iterator, JsonSerializable
{
    /**
     * @param mixed $element
     */
    public function add(mixed $element): void;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, mixed $value): void;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @return mixed
     */
    public function first(): mixed;

    /**
     * @return mixed
     */
    public function last(): mixed;

    /**
     * @return mixed
     */
    public function reset(): mixed;

    /**
     * @return mixed
     */
    public function end(): mixed;

    /**
     * @param callable $function
     *
     * @return self
     */
    public function map(callable $function): self;

    /**
     *
     */
    public function clear(): void;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return mixed[]
     */
    public function toArray(): array;

    /**
     * @param string $name
     *
     * @return $this|mixed|null
     */
    public function __get(string $name);

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set(string $name, mixed $value);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool;
}
