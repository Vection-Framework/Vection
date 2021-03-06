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

/*
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator;

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
class Violation implements ViolationInterface, \JsonSerializable
{
    /** @var mixed */
    protected $value;
    protected array $constraints;
    protected string $message;
    protected string $result = '';
    protected string $subject;

    /**
     * Violation constructor.
     *
     * @param string $subject
     * @param mixed  $value
     * @param array  $constraints
     * @param string $message
     */
    public function __construct(string $subject, $value, array $constraints, string $message)
    {
        $this->subject     = $subject;
        $this->value       = $value;
        $this->constraints = $constraints;
        $this->message     = $message;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
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
     * Specify data which should be serialized to JSON
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'property' => $this->subject,
            'actual' => $this->valueToString($this->value),
            'message' => $this->getMessage()
        ];
    }

    /**
     * Converts the given value into a string representation.
     *
     * @param mixed $value
     *
     * @return string
     */
    private function valueToString($value): string
    {
        switch (gettype($value)) {
            case 'integer':
            case 'double':
            case 'string':  return (string) $value;
            case 'boolean': return $value ? 'true' : 'false';
            case 'NULL':    return 'null';
            case 'array':   return 'Array(' . implode(', ', $value) . ')';
            case 'object':  return 'Object';
            default:        return '<unsupported-type>';
        }
    }
}
