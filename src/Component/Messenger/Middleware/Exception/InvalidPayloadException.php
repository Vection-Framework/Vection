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

namespace Vection\Component\Messenger\Middleware\Exception;

use Throwable;
use Vection\Component\Messenger\MessageBusException;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class InvalidPayloadException
 *
 * @package Vection\Component\Messenger\Middleware\Exception
 *
 * @author  David Lung <vection@davidlung.de>
 */
class InvalidPayloadException extends MessageBusException
{
    /**
     * @var ViolationInterface[]
     */
    protected $violations;

    /**
     * InvalidPayloadException constructor.
     *
     * @param array          $violations
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(array $violations, $code = 0, Throwable $previous = null)
    {
        parent::__construct('The message payload contains invalid data.', $code, $previous);
        $this->violations = $violations;
    }

    /**
     * Returns an array that contains violation objects mapped by subject name.
     *
     * @return ViolationInterface[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }
}