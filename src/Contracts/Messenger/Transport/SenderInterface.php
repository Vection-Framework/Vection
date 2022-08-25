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

declare(strict_types=1);

namespace Vection\Contracts\Messenger\Transport;

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface SenderInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface SenderInterface
{
    /**
     * Sends the serialized and optional encoded message via transport provider.
     * This methods takes optional a tag as second parameter, which can be optionally used
     * for common messaging purposes.
     *
     * @param MessageInterface $message
     *
     * @return MessageInterface
     */
    public function send(MessageInterface $message): MessageInterface;
}
