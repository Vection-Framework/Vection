<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\MessageBus\Exception;

use Exception;
use Throwable;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class InvalidPayloadException
 *
 * @package Vection\Component\MessageBus\Exception
 */
class InvalidPayloadException extends Exception
{
    /** @var ViolationInterface[] */
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
        parent::__construct(
            "The request payload contains values that not match the defined rules by validators.",
            $code,
            $previous
        );
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