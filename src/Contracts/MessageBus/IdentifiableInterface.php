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

namespace Vection\Contracts\MessageBus;

/**
 * Interface IdentifiableInterface
 *
 * @package Vection\Contracts\MessageBus\Contracts
 */
interface IdentifiableInterface
{
    /**
     * Returns an instance of IdentityInterface.
     *
     * @return IdentifierInterface
     */
    public function getIdentity(): IdentifierInterface;
}
