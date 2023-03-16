<?php

declare(strict_types=1);

namespace Vection\Component\Common;

use ArrayAccess;
use Countable;
use Iterator;
use JsonSerializable;
use LogicException;
use Vection\Component\Common\Exception\IOException;
use Vection\Component\Common\Exception\RuntimeException;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;
use Vection\Contracts\Validator\Schema\SchemaExceptionInterface;
use Vection\Contracts\Validator\Schema\SchemaValidatorInterface;

/**
 * Class VArray
 *
 * @package Vection\Component\Common
 * @author  David Lung <david.lung@appsdock.de>
 */
class VArray implements Countable, ArrayAccess, Iterator, JsonSerializable
{
    /** @var mixed[] */
    protected array $data;
    protected bool  $immutable;

    # region Parsing

    /**
     * Reads the structured content of a file and returns a
     * VArray instance which contains the data from the file.
     *
     * @param string $filePath A JSON or Yaml formatted file.
     * @param bool   $immutable
     *
     * @return VArray
     */
    public static function parseFile(string $filePath, bool $immutable = false): VArray
    {
        if (!is_file($filePath)) {
            throw new RuntimeException("VArray::fromFile(): File does not exist ($filePath).");
        }

        if (!in_array($ext = pathinfo($filePath, PATHINFO_EXTENSION), ['json', 'yaml', 'yml'])) {
            throw new RuntimeException('Unsupported file format. Supported formats are json, yaml.');
        }

        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new IOException("VArray::fromFile: Unable to read file ($filePath).");
        }

        if (in_array($ext, ['yaml', 'yml'])) {
            return self::parseYAML($content, $immutable);
        }

        if ($ext === 'json') {
            return self::parseJSON($content, $immutable);
        }

        return new VArray();
    }

    /**
     * @param string $yaml
     * @param bool   $immutable
     *
     * @return VArray
     */
    public static function parseYAML(string $yaml, bool $immutable = false): VArray
    {
        return Yaml::parse($yaml, $immutable);
    }

    /**
     * @param string $json
     * @param bool   $immutable
     *
     * @return VArray
     */
    public static function parseJSON(string $json, bool $immutable = false): VArray
    {
        return Json::parse($json, $immutable);
    }

    # endregion

    /**
     * @param mixed[] $data
     * @param bool    $immutable
     */
    public function __construct(array $data = [], bool $immutable = false)
    {
        $this->data = $data;
        $this->immutable = $immutable;
    }

    # region VArray Methods

    /**
     * @param mixed[] $value
     *
     * @return VArray
     */
    public function add(mixed $value): VArray
    {
        $this->assertMutable();
        $this->data[] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return VArray
     */
    public function set(string $key, mixed $value): VArray
    {
        $this->assertMutable();
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param bool   $usePathNotation
     *
     * @return mixed
     */
    public function get(string $key, bool $usePathNotation = true): mixed
    {
        return strpos($key, '.') > -1 && $usePathNotation ? $this->pathGet($this->data, $key) : ($this->data[$key] ?? null);
    }

    /**
     * @param mixed[] $data
     * @param string  $name
     *
     * @return mixed|null
     */
    private function pathGet(array $data, string $name): mixed
    {
        $parts = explode('.', $name);

        if (!array_key_exists($key = array_shift($parts), $data)) {
            return null;
        }

        $data = $data[$key];
        $name = implode('.', $parts);

        if ($name && is_array($data)) {
            return $this->pathGet($data, $name);
        }

        return $data;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @return mixed
     */
    public function first(): mixed
    {
        $index = array_keys($this->data)[0] ?? null;
        return $index ? $this->data[$index] : null;
    }

    /**
     * @return mixed
     */
    public function last(): mixed
    {
        if ($keys = array_keys($this->data)) {
            $index = $keys[count($keys) - 1];
            return $this->data[$index];
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function shift(): mixed
    {
        return array_shift($this->data);
    }

    /**
     * @return mixed
     */
    public function pop(): mixed
    {
        return array_pop($this->data);
    }

    /**
     *
     */
    public function map(callable $function): VArray
    {
        $result = [];

        foreach ($this->data as $key => $element) {
            $result[$key] = $function($element, $key, $this);
        }

        return new self($result, $this->immutable);
    }

    private function assertMutable(): void
    {
        if ($this->immutable === true){
            throw new LogicException('An immutable collection cannot be changed.');
        }
    }

    public function toArray(): array
    {
        return $this->data;
    }

    # endregion

    # region Advanced Methods

    /**
     * @throws SchemaExceptionInterface
     * @throws PropertyExceptionInterface
     */
    public function validate(SchemaValidatorInterface $schemaValidator): void
    {
        $schemaValidator->validateArray($this->data);
    }

    # endregion

    # region ArrayAccess

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function &offsetGet($offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        $this->assertMutable();
        $this->data[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        $this->assertMutable();
        unset($this->data[$offset]);
    }

    # endregion

    # region Iterator

    /**
     * @inheritDoc
     */
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * @inheritDoc
     */
    public function current(): mixed
    {
        return current($this->data);
    }

    /**
     * @inheritDoc
     */
    public function valid(): bool
    {
        return array_key_exists(key($this->data), $this->data);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        reset($this->data);
    }

    /**
     * @inheritDoc
     */
    public function next(): void
    {
        next($this->data);
    }

    # endregion

    # region Interfaces

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): mixed
    {
        return $this->data;
    }

    # endregion

    # region Magic Methods

    public function &__get(string $name): mixed
    {
        return $this->data[$name];
    }

    public function __set(string $name, mixed $value): void
    {
        $this->assertMutable();
        $this->set($name, $value);
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __toString()
    {
        return "VArray({$this->count()})";
    }

    # endregion
}
