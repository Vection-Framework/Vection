<?php

namespace Vection\Component\CQRS\Query;

use Vection\Contracts\CQRS\Query\ReadModelInterface;

/**
 * Class ReadModel
 *
 * @package Vection\Component\CQRS\Query
 */
class ReadModel implements ReadModelInterface
{
    /**
     * @inheritdoc
     */
    public function isEmpty(): bool
    {
        return \count(\array_filter(\get_object_vars($this))) <= 1;
    }

    /**
     * @return \array[]
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('ReadModel@%s (%s)', md5($this->toJson()), \get_class($this));
    }

    /**
     * @inheritdoc
     */
    public function toJson(): string
    {
        return \json_encode($this);
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
        $data = \get_object_vars($this);

        if ( ! \array_filter($data) ) {
            return [];
        }

        if ( $this instanceof ReadModelCollection && $data[$data['listKey']] ) {
            $listKey = $data['listKey'];
            /** @var ReadModel $item */
            foreach ( $data[$listKey] as $key => $item ) {
                unset($data[$listKey][$key]);
                $data[$listKey][$key] = $item->toArray();
            }
        }

        foreach ( $data as $key => $value ) {
            if ( $value instanceof self ) {
                $value = $value->toArray();
            }
            if ( $key[0] === '_' ) {
                if ( $value !== null ) {
                    $data[\substr($key, 1)] = $value;
                }
                unset($data[$key]);
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}