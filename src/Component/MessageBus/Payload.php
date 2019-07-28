<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function get($key): ? string
    {
        return $this->data[$key] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getPayload($key): ?PayloadInterface
    {
        if ( $value = $this->get($key) ) {
            return new Payload(\is_array($value) ? $value : [ $value ]);
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