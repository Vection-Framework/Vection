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

namespace Vection\Component\CQRS\Common;

use Vection\Contracts\CQRS\Common\PayloadInterface;

/**
 * Class Payload
 *
 * @package Vection\Component\CQRS\Common
 */
class Payload implements PayloadInterface
{
    /**
     * This property contains all the data that
     * were set by the setter method.
     *
     * @var array
     */
    protected $data;

    /**
     * Payload constructor.
     *
     * @param array $data Initial data key value pairs.
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return \array_key_exists($key, $this->data) ? $this->data[$key] : null;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function pop($key)
    {
        if( $value = $this->get($key) ){
            unset($this->data[$key]);
            return $value;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getPayload($key): ? PayloadInterface
    {
        if( $value = $this->get($key) ){
            return new Payload( \is_array($value) ? $value : [$value] );
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->data;
    }
}