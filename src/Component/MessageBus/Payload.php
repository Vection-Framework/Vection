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

declare(strict_types=1);

namespace Vection\Component\MessageBus;

use Vection\Contracts\MessageBus\PayloadInterface;

/**
 * Class Payload
 *
 * @package Vection\Component\MessageBus
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
    public function get(string $key): ? string
    {
        $value = $this->data[$key] ?? null;
        return $value === null ? $value : (string) $value;
    }

    /**
     * @inheritdoc
     */
    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getPayload($key): ? PayloadInterface
    {
        if ( $value = $this->get($key) ) {
            return new Payload([ $value ]);
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

    /**
     * Returns a fingerprint from containing data.
     *
     * @return string
     */
    public function getFingerprint(): string
    {
        return md5(json_encode($this->data));
    }
}