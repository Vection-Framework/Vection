<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Http;

/**
 * Class Headers
 *
 * @package Vection\Component\Http
 */
class Headers
{
    /** @var array */
    protected $headers;

    /** @var array */
    protected $names;

    /**
     * Headers constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        $this->headers = $headers;

        foreach( $headers as $name => $value ){
            $this->names[strtolower($name)] = $name;
        }
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->names[strtolower($name)]);
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return bool
     */
    public function hasValue(string $name, string $value): bool
    {
        $lowerName = strtolower($name);
        return isset($this->names[$lowerName]) && in_array($value, $this->headers[$this->names[$lowerName]]);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function get(string $name): array
    {
        $lowerName = strtolower($name);

        if( ! isset($this->names[$lowerName]) ){
            return [];
        }

        return $this->headers[$this->names[$lowerName]];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getLine(string $name): string
    {
        $lowerName = strtolower($name);

        if( ! isset($this->names[$lowerName]) ){
            return '';
        }

        return implode(', ', $this->get($name));
    }

    /**
     * @param string            $name
     * @param string|string[]   $value
     */
    public function add(string $name, $value): void
    {
        $lowerName = strtolower($name);

        if( ! isset($this->names[$lowerName]) ){
            $this->names[$lowerName] = $name;
        }

        $values = $this->headers[$this->names[$lowerName]] ?? [];

        if( is_string($value) && strpos($value, ',') !== false ){
            # if the value is comma separated, then make array from it to aware consistency
            $value = array_map('trim', explode(',', $value));
        }

        if( ! is_array($value) ){
            $values[] = $value;
        }else{
            $values = array_merge($values, $value);
        }

        $this->headers[$this->names[$lowerName]] = array_unique($values);
    }

    /**
     * @param string            $name
     * @param string|string[]   $value
     */
    public function set(string $name, $value): void
    {
        if( is_string($value) && strpos($value, ',') !== false ){
            # if the value is comma separated, then make array from it to aware consistency
            $value = array_map('trim', explode(',', $value));
        }

        $lowerName = strtolower($name);

        if( ! isset($this->names[$lowerName]) ){
            $this->names[$lowerName] = $name;
        }

        $this->headers[$this->names[$lowerName]] = is_array($value) ? $value : [$value];
    }

    /**
     * @param string $name
     */
    public function remove(string $name): void
    {
        $lowerName = strtolower($name);

        if( isset($this->names[$lowerName]) ){
            unset($this->headers[$this->names[$lowerName]]);
            unset($this->names[$lowerName]);
        }
    }

    /**
     * Returns the value of the header Content-Type.
     *
     * @return string
     */
    public function getContentType(): string
    {
        if( ! $this->has('content-type') ){
            return 'text/plain';
        }

        $contentType = explode(';', $this->getLine('content-type'))[0];

        return trim($contentType);
    }

    /**
     * Returns the value of the header Content-Type.
     *
     * @return int
     */
    public function getContentLength(): int
    {
        return (int) $this->getLine('content-length') ?: 0;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->headers;
    }

}