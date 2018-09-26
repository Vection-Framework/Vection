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

namespace Vection\Component\Validator;

use Vection\Contracts\Validator\ValidationFailedExceptionInterface;

/**
 * Class ValidationFailedException
 *
 * @package Vection\Component\Validator\Validator
 */
class ValidationFailedException extends \Exception implements ValidationFailedExceptionInterface
{
    /**
     * This property contains the validated value.
     *
     * @var mixed
     */
    protected $value;

    /**
     * This property contains all constraints which
     * were used to validate the value.
     *
     * @var array
     */
    protected $constraints;

    /**
     * ValidationFailedException constructor.
     *
     * @param string $message
     * @param        $value
     * @param array  $constraints
     * @param int    $code
     */
    public function __construct(string $message, $value, array $constraints = [], int $code = 0)
    {
        parent::__construct($message, $code);
        $this->value = $value;
        $this->constraints = $constraints;
    }

    /**
     * Returns the validated value.
     *
     * @return mixed
     */
    public function getValidatedValue()
    {
        return $this->value;
    }

    /**
     * Returns the constraints that were used to validate the value.
     *
     * @return array
     */
    public function getConstraints(): array
    {
        return $this->constraints;
    }
}