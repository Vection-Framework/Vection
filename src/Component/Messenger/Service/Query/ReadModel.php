<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Messenger\Service\Query;

use Vection\Component\Messenger\Exception\RuntimeException;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;

/**
 * Class ReadModel
 *
 * @package Vection\Component\Messenger\Service\Query
 *
 * @author  David Lung <vection@davidlung.de>
 */
class ReadModel implements ReadModelInterface
{
    /**
     * @param array  $data
     * @param string $key
     *
     * @return int|null
     */
    protected function getInt(array $data, string $key): ?int
    {
        if (array_key_exists($key, $data)) {
            return (int) $data[$key];
        }

        return null;
    }

    /**
     * @param array  $data
     * @param string $key
     *
     * @return string|null
     */
    protected function getString(array $data, string $key): ?string
    {
        if (array_key_exists($key, $data)) {
            return (string) $data[$key];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function toJson(): string
    {
        $json = json_encode($this);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Json Encode Error: '.json_last_error_msg());
        }

        return $json;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        foreach ($properties as $name => $value) {

            if ($value instanceof ReadModelInterface) {
                $value = $value->toArray();
                $properties[$name] = $value;
            }

            if (strpos($name, '_') === 0) {
                if ($value !== null) {
                    $properties[substr($name, 1)] = $value;
                }
                unset($properties[$name]);
            }
        }

        return $properties;
    }

    /**
     * @return array
     */
    public function __debugInfo(): array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s@%s.%s',
            str_replace('\\', '.', get_class($this)),
            spl_object_id($this),
            md5($this->toJson())
        );
    }
}
