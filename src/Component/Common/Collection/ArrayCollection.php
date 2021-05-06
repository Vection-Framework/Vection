<?php

/*
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Common\Collection;

use ArrayIterator;
use LogicException;
use Vection\Contracts\Common\Collection\CollectionInterface;

/**
 * Class ArrayCollection
 *
 * @package Vection\Component\Common\Collection
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ArrayCollection implements CollectionInterface
{
    protected array $elements;
    protected bool $immutable;

    /**
     * ArrayCollection constructor.
     *
     * @param array $elements
     * @param bool  $immutable
     */
    public function __construct(array $elements = [], bool $immutable = false)
    {
        $this->elements = $elements;
        $this->immutable = $immutable;
    }

    /**
     * @param mixed $element
     */
    public function add($element): void
    {
        $this->assertMutable();
        $this->elements[] = $element;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void
    {
        $this->assertMutable();
        $this->elements[$key] = $value;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->elements[$key];
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->elements);
    }

    /**
     * @return mixed
     */
    public function first()
    {
        $index = array_keys($this->elements)[0] ?? null;
        return $index ? $this->elements[$index] : null;
    }

    /**
     * @return mixed
     */
    public function last()
    {
        if ($keys = array_keys($this->elements)) {
            $index = $keys[count($keys) - 1];
            return $this->elements[$index];
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function reset()
    {
        return reset($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return $this->key() !== null;
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->reset();
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function end()
    {
        return end($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function map(callable $function): self
    {
        $result = [];

        foreach ($this->elements as $key => $element) {
            $result[$key] = $function($element, $key, $this);
        }

        return new self($result);
    }

    /**
     * @inheritDoc
     */
    public function clear(): void
    {
        $this->assertMutable();
        $this->elements = [];
    }

    /**
     * @inheritDoc
     */
    public function isEmpty(): bool
    {
        return count($this->elements) < 1;
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->assertMutable();
        $this->set($offset, $value);
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->assertMutable();
        unset($this->elements[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * @inheritDoc
     */
    public function __set(string $name, $value)
    {
        $this->assertMutable();
        $this->set($name, $value);
    }

    /**
     * @inheritDoc
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    private function assertMutable(): void
    {
        if ($this->immutable === true){
            throw new LogicException('An immutable collection cannot be changed.');
        }
    }

}
