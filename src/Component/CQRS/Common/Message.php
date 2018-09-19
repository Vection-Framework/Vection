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

use Vection\Contracts\CQRS\Common\MessageInterface;
use Vection\Contracts\CQRS\Common\PayloadInterface;

/**
 * Class Message
 *
 * @package Vection\Component\CQRS\Common
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