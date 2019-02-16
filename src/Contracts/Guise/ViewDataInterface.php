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

namespace Vection\Contracts\Guise;

/**
 * Interface ViewDataInterface
 *
 * @package Vection\Contracts\Guise
 */
interface ViewDataInterface
{

    /**
     * Sets the parent data container from which this
     * data contains will be extends.
     *
     * @param ViewDataInterface $dataContainer
     */
    public function extend(ViewDataInterface $dataContainer): void;

    /**
     * Adds new key value pair.
     *
     * @param string $key
     * @param        $value
     */
    public function add(string $key, $value): void;

    /**
     * Returns a value by given key or null if key doesn't exists.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * Returns all the data of this container by considering
     * the data of the parent container.
     *
     * @return array
     */
    public function toArray(): array;
}