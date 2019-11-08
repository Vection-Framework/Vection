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

namespace Vection\Component\Messenger\Service\Query;

use InvalidArgumentException;
use Vection\Contracts\Messenger\Service\Query\ReadModelInterface;
use function json_last_error_msg;
use function substr;

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
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return count(array_filter(get_object_vars($this))) <= 1;
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
        return sprintf('ReadModel@%s (%s)', md5($this->toJson()), get_class($this));
    }

    /**
     * @inheritdoc
     */
    public function toJson(): string
    {
        $json = json_encode($this);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Json Encode Error: '.json_last_error_msg());
        }
        return $json;
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
    public function toArray(): array
    {
        $data = get_object_vars($this);

        if (! array_filter($data)) {
            return [];
        }

        if ($this instanceof ReadModelCollection) {
            $listKey = $data['listKey'];

            if ($data['listKey'] !== 'items') {
                $data[$listKey] = $data['items'];
                unset($data['items']);
            }

            // @var ReadModel $item
            foreach ($data[$listKey] as $key => $item) {
                unset($data[$listKey][$key]);
                if ($item instanceof self) {
                    $data[$listKey][$key] = $item->toArray();
                } else {
                    $data[$listKey][$key] = (array) $item;
                }
            }
        }

        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $value = $value->toArray();
            }
            if (strpos($key, '_') === 0) {
                if ($value !== null) {
                    $data[substr($key, 1)] = $value;
                }
                unset($data[$key]);
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
