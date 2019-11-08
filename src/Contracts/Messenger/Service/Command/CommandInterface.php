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

namespace Vection\Contracts\Messenger\Service\Command;

/**
 * Interface CommandInterface
 *
 * @package Vection\Contracts\Messenger\Service\Command
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface CommandInterface
{
    /**
     * Returns an unique global id if provided, otherwise null.
     * This id should identify the change made by the handler
     * of this command.
     *
     * @return string|null
     */
    public function getId(): ?string;
}