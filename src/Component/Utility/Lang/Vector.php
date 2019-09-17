<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Lang;


use Traversable;

/**
 * Class Vector
 *
 * @package Vection\Component\Utility\Lang;
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class Vector implements \Countable, \ArrayAccess, \IteratorAggregate
{
    /** @var array|\ArrayAccess */
    private $list;

    /**
     * Vector constructor.
     *
     * @param array|\ArrayAccess $list
     */
    public function __construct($list)
    {
        if( $list instanceof \ArrayAccess ) {
            $this->list = (array)$list;
        } else if( \is_array($list) ) {
            $this->list = $list;
        }
        throw new \InvalidArgumentException('Argument Object must be ArrayAccess or native array');
    }

    /**
     * @param mixed $item
     */
    public function append($item): void
    {
        $this->list[] = $item;
    }

    /**
     * @param iterable $items
     */
    public function extend(iterable $items): void
    {
        foreach( $items as $item ) {
            $this->append($item);
        }
    }

    /**
     * @return array
     */
    public function split(): array
    {
        return [array_keys($this->list), array_values($this->list)];
    }

    /**
     * @return array
     */
    public function first(): array
    {
        return \array_slice($this->list, 0, 1);
    }

    /**
     * @return array
     */
    public function last(): array
    {
        $tmp = \array_reverse($this->list, true);
        return \array_slice($tmp, 0, 1);
    }

    /**
     * @param callable                $function
     * @param array|\ArrayAccess|null $list
     *
     * @return array
     */
    public function map(callable $function, ?array $list = null): array
    {
        $result = [];

        $list = $list ?: $this->list;

        foreach( $list as $key => $item ) {
            if( \is_array($item) ) {
                $result[$key] = $this->map($function, $item);
            } else {
                $result[$key] = $function($item);
            }
        }
        return $result;
    }

    /**
     * @param string $prefix
     *
     * @return array
     */
    public function prefixKey(string $prefix): array
    {
        $result = [];

        foreach( $this->list as $key => $item ) {
            $result[$prefix . $key] = $item;
        }

        return $result;
    }

    /**
     *
     */
    public function sort(): void
    {

    }

    /**
     * @return array
     */
    public function pluck(): array
    {
        return $this->list;
    }

    /**
     * @param mixed $value
     * @param mixed $key
     */
    public function insert($key, $value): void
    {
        $this->list[$key] = $value;
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->list = [];
    }


    //region BOOLEANS

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return \count($this->list) < 1;
    }

    /**
     * @return bool
     */
    public function isAssoc(): bool
    {
        $keys = array_keys($this->list);
        return $keys !== array_keys($this->list);
    }

    /**
     * @return bool
     */
    public function isNummeric(): bool
    {
        $keys = array_keys($this->list);
        return $keys === array_keys($this->list);
    }


    //endregion

    /**
     * Retrieve an external iterator
     * @link  https://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    /**
     * Whether a offset exists
     * @link  https://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     * @link  https://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     * @link  https://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link  https://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * Count elements of an object
     * @link  https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return \count($this->list);
    }
}