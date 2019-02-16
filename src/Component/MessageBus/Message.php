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

use Vection\Contracts\MessageBus\MessageInterface;
use Vection\Contracts\MessageBus\PayloadInterface;

/**
 * Class Message
 *
 * @package Vection\Component\MessageBus
 */
abstract class Message implements MessageInterface
{
    /**
     * This property contains a string that is unique
     * over all created messages and which identifies
     * this message object.
     *
     * @var string
     */
    protected $msgID;

    /**
     * This property is an instance of \DateTime
     * which is created at the begin of the lifecycle of
     * this message object.
     *
     * @var \DateTime
     */
    protected $msgCreatedTime;

    /**
     * A payload object which contains all data required
     * for processing this message.
     *
     * @var PayloadInterface
     */
    protected $payload;

    /**
     * Message constructor.
     *
     * @param null|PayloadInterface $payload
     */
    public function __construct(? PayloadInterface $payload = null)
    {
        try {
            $this->msgID = \bin2hex(\random_bytes(16));
        } catch( \Exception $e ) {
            $this->msgID = \uniqid( (string) \time());
        }

        $this->payload = $payload ?: new Payload();

        try {
            $this->msgCreatedTime = new \DateTime();
        } catch( \Exception $e ) {
            # Never happens without construct param.
        }
    }

    /**
     * @inheritdoc
     */
    public function msgID(): string
    {
        return $this->msgID;
    }

    /**
     * @inheritdoc
     */
    public function msgCreatedTime(): \DateTime
    {
        return $this->msgCreatedTime;
    }

    /**
     * @inheritdoc
     */
    public function payload(): PayloadInterface
    {
        return $this->payload;
    }

    /**
     * Set a value to payload
     *
     * @param string $key
     * @param string $value
     */
    protected function set(string $key, string $value): void
    {
        $this->payload->set($key, $value);
    }

    /**
     * Get a value from payload
     *
     * @param string $key
     *
     * @return mixed|string|null
     */
    protected function get(string $key)
    {
        return $this->payload->get($key);
    }
}
