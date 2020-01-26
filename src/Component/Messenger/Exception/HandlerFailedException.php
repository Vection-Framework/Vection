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

namespace Vection\Component\Messenger\Exception;

use Exception;
use Throwable;

/**
 * Class HandlingFailedException
 *
 * @package Vection\Component\Messenger\Exception
 *
 * @author  David Lung <vection@davidlung.de>
 */
class HandlerFailedException extends Exception
{
    /**
     * HandlerFailedException constructor.
     *
     * @param Throwable|null $previous
     */
    public function __construct(Throwable $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
