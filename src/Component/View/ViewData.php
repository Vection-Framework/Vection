<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\View;

use Vection\Contracts\View\ViewDataInterface;

/**
 * Class ViewData
 *
 * @package Vection\Component\View
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
     * @var ViewDataInterface
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
     * @param mixed  $value
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