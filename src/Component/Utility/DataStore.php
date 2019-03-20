<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) David Lung <aleqz.lung@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility;


use Symfony\Component\Yaml\Yaml;

/**
 * Class DataStore
 *
 * @package Vection\Component\Utility
 */
class DataStore implements \JsonSerializable
{
    /** Content type json */
    public const TYPE_JSON = 'json';

    /** Content type yaml */
    public const TYPE_YAML = 'yaml';

    /** @var string */
    protected $type;

    /** @var string */
    protected $file;

    /** @var array */
    protected $data = [];

    /**
     * DataStore constructor.
     *
     * @param string $file
     * @param bool   $create
     *
     * @throws \Vection\Component\Utility\Exception\JsonException
     */
    public function __construct(string $file, bool $create = false)
    {
        $this->fromFile($file, $create);
    }

    /**
     * @param string $file
     * @param bool   $create
     *
     * @return DataStore
     * @throws Exception\JsonException
     */
    public function fromFile(string $file, bool $create = false): DataStore
    {
        if( $create && !\file_exists($file) && !\touch($file) ) {
            throw new \RuntimeException("DataStore: Could not create file store, check file permissions for {$file}");
        }

        $this->file = $file;

        try {
            switch( strtolower(pathinfo($file, PATHINFO_EXTENSION)) ) {
                case 'json':
                    $this->data = Json::decode($file);
                    $this->type = static::TYPE_JSON;
                    break;
                case 'yaml':
                case 'yml':
                    $this->type = static::TYPE_YAML;
                    $this->data = Yaml::parse(file_get_contents($file));
                    break;
                default:
                    throw new \RuntimeException("DataStore: Unsupported file type ({$file})");
            }
        } catch( \RuntimeException $ex ) {
            throw new \RuntimeException('DataStore: Cannot load from file.', 0, $ex);
        }

        return $this;
    }

    /**
     * @param $name
     *
     * @return mixed|null|object
     */
    public function __get($name)
    {
        $result = $this->get($name);

        return \is_array($result) ? (object)$result : $result;
    }

    public function __set($key, $value)
    {
        #__get should paired with __set
    }

    /**
     * @param array ...$keys
     *
     * @return mixed|null
     */
    public function get(...$keys)
    {
        if( \count($keys) === 1 && strpos($keys[0], '.') !== false ) {
            $keys = explode('.', $keys[0]);
        }

        $data = null;
        $key = array_shift($keys);
        $data = $this->data[$key] ?? null;

        if( $data && $keys ) {
            foreach( $keys as $key ) {
                if( !\is_array($data) || !isset($data[$key]) ) {
                    return null;
                }
                $data = $data[$key];
            }
        }

        return $data;
    }

    public function __isset($value)
    {
        #__set should paired with __isset
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function set(string $key, $value): void
    {
        if( $key ) {
            $keys = explode('.', $key);
            $key = array_shift($keys);

            if( !isset($this->data[$key]) ) {
                $this->data[$key] = [];
            }

            $data =& $this->data[$key];

            foreach( $keys as $k ) {
                if( !isset($data[$k]) ) {
                    $data[$k] = [];
                }
                $data =& $data[$k];
            }

            $data = $value;
        } else {
            $this->data = (array)$value;
        }
    }

    /**
     * Saves all data to file
     */
    public function save(): void
    {
        if( $this->type === self::TYPE_JSON ) {
            file_put_contents($this->file, json_encode($this->data, JSON_PRETTY_PRINT));
        }

        if( $this->type === self::TYPE_YAML ) {
            file_put_contents($this->file, Yaml::dump($this->data));
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
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
        switch( $this->type ) {
            case self::TYPE_JSON:
                return json_encode($this);
            case self::TYPE_YAML:
                return Yaml::dump($this->data);
            default:
                return '[UNSUPPORTED CONTENT TYPE]';
        }
    }
}