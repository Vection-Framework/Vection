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

namespace Vection\Component\Messenger\Exception;

use Throwable;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class ValidationFailedException
 *
 * @package Vection\Component\Messenger\Middleware\Exception
 *
 * @author  David Lung <vection@davidlung.de>
 */
class ValidationFailedException extends RuntimeException
{
    /**
     * @var ViolationInterface[]
     */
    protected array $violations;

    /**
     * @param mixed[]        $violations
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(array $violations, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('The message body contains invalid data.', $code, $previous);
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
