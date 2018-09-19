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

namespace Vection\Contracts\CQRS\Common;

/**
 * Interface IdentifiableInterface
 *
 * @package Vection\Contracts\CQRS\Common\Contracts
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