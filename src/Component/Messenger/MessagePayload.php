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

namespace Vection\Component\Messenger;

/**
 * Class MessagePayload
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MessagePayload
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Sets a new value to the internal data.
     *
     * @param string $name
     * @param mixed  $value
     */
    final protected function set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * Returns a value from internal data.
     *
     * @param string $name
     *
     * @return mixed|null
     */
    final protected function get(string $name)
    {
        return ($this->data[$name] ?? null);
    }

    /**
     * Returns the command data as array.
     *
     * @return array
     */
    final protected function toArray(): array
    {
        return $this->data;
    }
}