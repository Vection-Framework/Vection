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

namespace Vection\Component\Utility;

use Vection\Component\Utility\Exception\JsonException;

/**
 * Class Json
 *
 * @package Vection\Component\Utility
 */
class Json implements \JsonSerializable
{
    /** @var array */
    protected $data;

    /**
     * Json constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param string $json
     *
     * @return Json
     * @throws JsonException
     * @throws \InvalidArgumentException
     */
    public static function fromString(string $json): Json
    {
        return new self(self::decode($json));
    }

    /**
     * Decodes a json string or file.
     *
     * @param string $json
     * @param bool   $assoc
     * @param int    $depth
     * @param int    $options
     *
     * @throws JsonException
     *
     * @return array|\stdClass
     */
    public static function decode(string $json, bool $assoc = true, int $depth = 512, int $options = 0)
    {
        $filePath = '';

        if( $json[0] !== '{' ) {

            if( !\file_exists($json) ) {
                throw new JsonException('Invalid path or file does not exists: ' . $json, JsonException::NOT_FOUND);
            }

            $filePath = $json;
            $json = \file_get_contents($json);
        }

        $data = \json_decode($json, $assoc, $depth, $options);

        if( \json_last_error() !== JSON_ERROR_NONE ) {
            throw new JsonException($filePath, JsonException::INVALID);
        }

        return $data;
    }

    /**
     * @param string $filePath
     *
     * @return Json
     * @throws JsonException
     * @throws \InvalidArgumentException
     */
    public static function fromFile(string $filePath): Json
    {
        return new self(self::decode($filePath));
    }

    /**
     * Encodes an array to json formatted string.
     *
     * @param mixed $data
     * @param int   $options
     * @param int   $depth
     *
     * @return string
     * @throws JsonException
     */
    public static function encode($data, $options = 0, $depth = 512): string
    {
        $json = \json_encode($data, $options, $depth);
        if( \json_last_error() !== JSON_ERROR_NONE ) {
            throw new JsonException('Json Encode Error', JsonException::INVALID);
        }
        return $json;
    }

    /**
     * @param array|int ...$indexes
     *
     * @return mixed|null
     */
    public function getKeyByIndex(...$indexes)
    {
        $index = array_shift($indexes);
        $key = array_keys($this->data)[$index] ?? null;
        $data = null;

        if( $key && isset($this->data[$key]) ) {
            $data = $this->data[$key];
            if( \count($indexes) ) {
                foreach( $indexes as $i ) {
                    $key = array_keys($data)[$i] ?? null;
                    if( !isset($data[$key]) || !\is_array($data) ) {
                        return null;
                    }
                    $data = $data[$key];
                }
            }
        }

        return $key;
    }

    /**
     * @param array|int ...$indexes
     *
     * @return mixed|null
     */
    public function getByIndex(...$indexes)
    {
        $index = array_shift($indexes);
        $key = array_keys($this->data)[$index] ?? null;
        $data = null;

        if( $key && isset($this->data[$key]) ) {
            $data = $this->data[$key];
            if( \count($indexes) ) {
                foreach( $indexes as $index ) {
                    $key = array_keys($data)[$index] ?? null;
                    if( !isset($data[$key]) || !\is_array($data) ) {
                        return null;
                    }
                    $data = $data[$key];
                }
            }
        }

        return $data;
    }

    /**
     * @param string $keyPath
     *
     * @return bool
     */
    public function has(string $keyPath): bool
    {
        $keys = explode('.', $keyPath);
        $data = $this->data;

        while( $key = array_shift($keys) ) {
            if( !array_key_exists($key, $data) ) {
                return false;
            }
            $data = $data[$key];
        }

        return true;
    }

    /**
     * @param string $keyPath
     *
     * @return mixed|null
     */
    public function get(string $keyPath)
    {
        $keys = explode('.', $keyPath);
        $key = array_shift($keys);
        $data = null;

        if( isset($this->data[$key]) ) {
            $data = $this->data[$key];
            if( \count($keys) ) {
                foreach( $keys as $key ) {
                    if( !isset($data[$key]) || !\is_array($data) ) {
                        return null;
                    }
                    $data = $data[$key];
                }
            }
        }

        return $data;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Writes data to file if a file path is given.
     *
     * @param string $path
     */
    public function write(string $path): void
    {
        \file_put_contents($path, \json_encode($this->data, JSON_PRETTY_PRINT));
    }

    /**
     * Specify data which should be serialized to JSON
     * @link  http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return json_encode($this);
    }
}