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

namespace Vection\Contracts\Messenger;

/**
 * Interface MessageRelationInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MessageRelationInterface
{
    /**
     * @param string $id
     *
     * @return MessageRelationInterface
     */
    public function inCorrelation(string $id): self;

    /**
     * @param string $id
     *
     * @return MessageRelationInterface
     */
    public function causedBy(string $id): self;

    /**
     * @param MessageInterface $message
     *
     * @return MessageRelationInterface
     */
    public function with(MessageInterface $message): self;

    /**
     * @return MessageHeadersInterface
     */
    public function getHeaders(): MessageHeadersInterface;
}
