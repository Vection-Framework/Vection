<?php
/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator;

use JsonSerializable;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class Violation
 *
 * This class represents a violation of rules defined by a validator.
 * Every time a validator failed validation, an object of this class
 * will be created an returned. This class contains the information
 * about the invalid value and an user message.
 *
 * @package Vection\Component\Validator
 */
class Violation implements ViolationInterface, JsonSerializable
{
    protected mixed  $value;
    /** @var mixed[] */
    protected array  $constraints;
    protected string $message;
    protected string $result = '';
    protected string $subject;

    /**
     * @param string  $subject
     * @param mixed   $value
     * @param mixed[] $constraints
     * @param string  $message
     */
    public function __construct(string $subject, mixed $value, array $constraints, string $message)
    {
        $this->subject     = $subject;
        $this->value       = $value;
        $this->constraints = $constraints;
        $this->message     = $message;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        if ( ! $this->result ) {
            $constraints = ['{value}' => $this->valueToString($this->value)];

            foreach ( $this->constraints as $name => $constraint ) {
                $constraints['{'.$name.'}'] = $this->valueToString($constraint);
            }

            $this->result = str_replace(
                array_keys($constraints),
                array_values($constraints),
                $this->message ?: ''
            );
        }

        return $this->result;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getMessage();
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            'property' => $this->subject,
            'actual'   => $this->value,
            'message'  => $this->getMessage()
        ];
    }

    /**
     * Converts the given value into a string representation.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function valueToString(mixed $value): string
    {
        return match (gettype($value)) {
            'integer', 'double', 'string' => (string)$value,
            'boolean' => $value ? 'true' : 'false',
            'NULL' => 'null',
            'array' => 'Array',
            'object' => 'Object',
            default => '<unsupported-type>',
        };
    }
}
