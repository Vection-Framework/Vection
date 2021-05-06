<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Contracts\Common\Collection;

use ArrayAccess;
use Countable;
use Iterator;
use IteratorAggregate;
use JsonSerializable;

/**
 * Interface CollectionInterface
 *
 * @package Vection\Contracts\Common\Collection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
interface CollectionInterface extends Countable, ArrayAccess, Iterator, IteratorAggregate, JsonSerializable
{
    /**
     * @param mixed $element
     */
    public function add($element): void;

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function last();

    /**
     * @return mixed
     */
    public function reset();

    /**
     * @return mixed
     */
    public function end();

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
     * @return array
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
     * @param        $value
     */
    public function __set(string $name, $value);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name): bool;
}