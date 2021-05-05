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

namespace Vection\Contracts\Messenger;

/**
 * Interface MessageInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageInterface
{
    /**
     * Returns the an object that contains the header information
     * of this message.
     *
     * @return MessageHeadersInterface
     */
    public function getHeaders(): MessageHeadersInterface;

    /**
     * Returns the payload of this message.
     *
     * @return mixed
     */
    public function getBody();

    /**
     * Returns the type of the body. If the body is an object, the class name is returned.
     *
     * @return string
     */
    public function getBodyType(): string;

    /**
     * @param string $name
     * @param string $value
     *
     * @return static
     */
    public function withHeader(string $name, string $value): MessageInterface;

    /**
     * @param object $body
     *
     * @return static
     */
    public function withBody($body): MessageInterface;
}
