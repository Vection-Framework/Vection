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

namespace Vection\Component\Messenger;

use InvalidArgumentException;
use Vection\Contracts\Messenger\MessageBusMiddlewareInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceFactoryInterface;
use Vection\Contracts\Messenger\MiddlewareSequenceInterface;

/**
 * Class MiddlewareSequenceFactory
 *
 * @package Vection\Component\Messenger
 *
 * @author  David Lung <vection@davidlung.de>
 */
class MiddlewareSequenceFactory implements MiddlewareSequenceFactoryInterface
{
    /**
     * @param array $middleware
     *
     * @return MiddlewareSequenceInterface
     */
    public function create(array $middleware): MiddlewareSequenceInterface
    {
        foreach ($middleware as $m) {
            if (! $m instanceof MessageBusMiddlewareInterface) {
                throw new InvalidArgumentException(
                    'Expects an array of MessageBusMiddlewareInterface objects.'
                );
            }
        }

        return new MiddlewareSequence($middleware);
    }
}