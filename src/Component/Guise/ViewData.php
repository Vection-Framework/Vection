<?php
/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Guise;

use Vection\Contracts\Guise\ViewDataInterface;

/**
 * Class ViewData
 *
 * @package Vection\Component\Guise
 */
class ViewData implements ViewDataInterface
{
    /**
     * This property contains all key value pairs.
     *
     * @var array
     */
    protected $data = [];

    /**
     * This property can be the parent data container
     * from which all data will be extended while
     * converting to array.
     *
     * @var ViewData
     */
    protected $parent;

    /**
     * Sets the parent data container from which this
     * data contains will be extends.
     *
     * @param ViewDataInterface $dataContainer
     */
    public function extend(ViewDataInterface $dataContainer): void
    {
        $this->parent = $dataContainer;
    }

    /**
     * Adds new key value pair.
     *
     * @param string $key
     * @param $value
     */
    public function add(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Returns a value by given key or null if key doesn't exists.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * Returns all the data of this container by considering
     * the data of the parent container.
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = $this->data;

        if ( $this->parent ) {
            $data = \array_merge($this->parent->toArray(), $this->data);
        }

        return $data;
    }
}