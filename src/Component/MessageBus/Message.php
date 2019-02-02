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
        $this->msgID = \uniqid(\time());
        $this->msgCreatedTime = new \DateTime();
        $this->payload = $payload ?: new Payload();
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
}