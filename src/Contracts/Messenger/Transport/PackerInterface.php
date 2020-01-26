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

namespace Vection\Contracts\Messenger\Transport;

use Vection\Contracts\Messenger\MessageInterface;

/**
 * Interface PackerInterface
 *
 * @package Vection\Contracts\Messenger\Transport
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface PackerInterface
{
    /**
     * @param MessageInterface $message
     *
     * @return PackageInterface
     */
    public function pack(MessageInterface $message): PackageInterface;

    /**
     * @param PackageInterface $package
     *
     * @return MessageInterface
     */
    public function unpack(PackageInterface $package): MessageInterface;
}
