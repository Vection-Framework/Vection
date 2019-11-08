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
 * Interface MiddlewareSequenceFactoryInterface
 *
 * @package Vection\Contracts\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface MiddlewareSequenceFactoryInterface
{
    /**
     * Creates and returns an instance of MiddlewareSequenceInterface
     * that executes the given middleware objects sequentially
     * in the order they are pass.
     *
     * @param array $middleware
     *
     * @return MiddlewareSequenceInterface
     */
    public function create(array $middleware): MiddlewareSequenceInterface;
}