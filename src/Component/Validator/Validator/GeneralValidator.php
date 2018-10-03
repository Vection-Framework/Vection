<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 *  (c) Vection <project@vection.org>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Exception\ValidationFailedException;
use Vection\Contracts\Validator\ValidatorInterface;

/**
 * Class GeneralValidator
 *
 * @package Vection\Component\Validator\Validator
 */
class GeneralValidator implements ValidatorInterface
{

    /**
     * @param $value
     * @param array $constraints
     *
     * @throws ValidationFailedException
     */
    public function validate($value, array $constraints): void
    {
        $assertion = \array_shift($constraints);
        \array_unshift($constraints, $value);
        method_exists($this, $assertion) && $this->$assertion(...$constraints);
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function alnum($value, string $message = ''): void
    {
        if ( ! \preg_match('/^([a-zA-Z]{1}[a-zA-Z0-9]*)$/', $value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not alphanumeric.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function betweenValue($value, int $min, int $max, string $message = ''): void
    {
        if ( $value <= $min || $value >= $max ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is neither greater than or equal to "%s" nor less than or equal to "%s".',
                $value, $max, $min
            );

            throw new ValidationFailedException($message, $value, [ 'min' => $min, 'max' => $max ]);
        }
    }

    /**
     * @param        $value
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function betweenLength($value, int $min, int $max, string $message = ''): void
    {
        $len = \strlen($value);
        if ( $len <= $min || $len >= $max ) {
            $message = \sprintf(
                $message ?: 'Length of "%s" is neither greater than or equal to "%s" nor less than or equal to "%s".',
                $value, $max, $min
            );

            throw new ValidationFailedException($message, $value, [ 'min' => $min, 'max' => $max ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function boolean($value, string $message = ''): void
    {
        if ( ! \is_bool($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a boolean.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param array  $needle
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function contains($value, array $needle, string $message = ''): void
    {
        if ( ! \in_array($needle, $value, true) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" does not contains %s.',
                $value, $needle
            );

            throw new ValidationFailedException($message, $value, [ 'needle' => $needle ]);
        }
    }

    /**
     * @param        $value
     * @param array  $list
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function choice($value, array $list, string $message = ''): void
    {
        if ( ! \in_array($value, $list, true) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a member of list.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notEmpty($value, string $message = ''): void
    {
        if ( empty($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is empty, but non empty value was expected',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $format
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function date($value, string $format, string $message = ''): void
    {
        $dateTime = \DateTime::createFromFormat('!' . $format, $value);

        if ( false === $dateTime || $value !== $dateTime->format($format) ) {
            $message = \sprintf(
                $message ?: 'Date "%s" is invalid or does not match format "%s".',
                $value, $format
            );

            throw new ValidationFailedException($message, $value, [ 'format' => $format ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function digit($value, string $message = ''): void
    {
        if ( ! \ctype_digit((string)$value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid digit.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function directory($value, string $message = ''): void
    {
        if ( ! \is_dir($value) ) {
            $message = \sprintf(
                $message ?: 'Path "%s" is not a valid directory.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function email($value, string $message = ''): void
    {
        if ( ! \filter_var($value, FILTER_VALIDATE_EMAIL) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid email address.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $char
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function endsWith($value, string $char, string $message = ''): void
    {
        if ( \substr($value, -1) !== $char ) {
            $message = \sprintf(
                $message ?: 'Value "%s" does not ends with "%s".',
                $value, $char
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function false($value, string $message = ''): void
    {
        if ( $value !== false ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not "false", "false" was expected.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function true($value, string $message = ''): void
    {
        if ( $value !== true ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not "true", "true" was expected.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function file($value, string $message = ''): void
    {
        if ( ! \is_file($value) ) {
            $message = \sprintf(
                $message ?: 'Path "%s" is not a valid file.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param int    $limit
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function greaterOrEqualThan($value, int $limit, string $message = ''): void
    {
        if ( $value < $limit ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not greater or equal than "%s".',
                $value, $limit
            );

            throw new ValidationFailedException($message, $value, [ 'limit' => $limit ]);
        }
    }

    /**
     * @param        $value
     * @param int    $limit
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function greaterThan($value, int $limit, string $message = ''): void
    {
        if ( $value <= $limit ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not greater than "%s".',
                $value, $limit
            );

            throw new ValidationFailedException($message, $value, [ 'limit' => $limit ]);
        }
    }

    /**
     * @param        $value
     * @param array  $array
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function inArray($value, array $array, string $message = ''): void
    {
        if ( ! \in_array($value, $array, true) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not an element of the given values.',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'array' => $array ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function integer($value, string $message = ''): void
    {
        if ( ! \is_int($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not an integer.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function ipv4($value, string $message = ''): void
    {
        if ( ! \filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid ipv4 address.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function ipv6($value, string $message = ''): void
    {
        if ( ! \filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid ipv6 address.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function isArray($value, string $message = ''): void
    {
        if ( ! \is_array($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not an array, but an array was expected.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function isJsonString($value, string $message = ''): void
    {
        if ( null === \json_decode($value) && JSON_ERROR_NONE !== \json_last_error() ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid json string.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param int    $length
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function length($value, int $length, string $message = ''): void
    {
        if ( \strlen($value) !== $length ) {
            $message = \sprintf(
                $message ?: 'Value "%s" has to be %d exactly characters long, but length is %d.',
                $value, $length, \strlen($value)
            );

            throw new ValidationFailedException($message, $value, [ 'length' => $length ]);
        }
    }

    /**
     * @param        $value
     * @param int    $limit
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function lessOrEqualThan($value, int $limit, string $message = ''): void
    {
        if ( $value > $limit ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not less or equal than "%s".',
                $value, $limit
            );

            throw new ValidationFailedException($message, $value, [ 'limit' => $limit ]);
        }
    }

    /**
     * @param        $value
     * @param int    $limit
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function lessThan($value, int $limit, string $message = ''): void
    {
        if ( $value >= $limit ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not less than "%s".',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'limit' => $limit ]);
        }
    }

    /**
     * @param        $value
     * @param int    $max
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function max($value, int $max, string $message = ''): void
    {
        if ( $value > $max ) {
            $message = \sprintf(
                $message ?: 'Number "%s" was expected to be at most "%s".',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'max' => $max ]);
        }
    }

    /**
     * @param        $value
     * @param int    $length
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function maxLength($value, int $length, string $message = ''): void
    {
        if ( \strlen($value) > $length ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is too long, it should have no more than %d characters, but has %d characters.',
                $value, $length, \strlen($value)
            );

            throw new ValidationFailedException($message, $value, [ 'length' => $length ]);
        }
    }

    /**
     * @param        $value
     * @param int    $limit
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function min($value, int $limit, string $message = ''): void
    {
        if ( $value < $limit ) {
            $message = \sprintf(
                $message ?: 'Number "%s" was expected to be at least "%s".',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'limit' => $limit ]);
        }
    }

    /**
     * @param        $value
     * @param int    $length
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function minLength($value, int $length, string $message = ''): void
    {
        if ( \strlen($value) < $length ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is too short, it should have at least %d characters, but only has %d characters.',
                $value, $length, \strlen($value)
            );

            throw new ValidationFailedException($message, $value, [ 'length' => $length ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notBlank($value, string $message = ''): void
    {
        if ( ! \trim($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is blank, but was expected to contain a value.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param        $equalValue
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notEquals($value, $equalValue, string $message = ''): void
    {
        if ( $value === $equalValue ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is equal to expected value "%s".',
                $value, $equalValue
            );

            throw new ValidationFailedException($message, $value, [ 'equalValue' => $equalValue ]);
        }
    }

    /**
     * @param        $value
     * @param        $equalValue
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function equals($value, $equalValue, string $message = ''): void
    {
        if ( $value !== $equalValue ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not equal to expected value "%s".',
                $value, $equalValue
            );

            throw new ValidationFailedException($message, $value, [ 'equalValue' => $equalValue ]);
        }
    }

    /**
     * @param        $value
     * @param        $sameValue
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notSame($value, $sameValue, string $message = ''): void
    {
        if ( $value === $sameValue ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is the same as expected value "%s".',
                $value, $sameValue
            );

            throw new ValidationFailedException($message, $value, [ 'sameValue' => $sameValue ]);
        }
    }

    /**
     * @param        $value
     * @param        $sameValue
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function same($value, $sameValue, string $message = ''): void
    {
        if ( $value !== $sameValue ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not the same as expected value "%s".',
                $value, $sameValue
            );

            throw new ValidationFailedException($message, $value, [ 'sameValue' => $sameValue ]);
        }
    }

    /**
     * @param        $value
     * @param array  $array
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notInArray($value, array $array, string $message = ''): void
    {
        if ( \in_array($value, $array, true) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is in given array.',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'array' => $array ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function notNull($value, string $message = ''): void
    {
        if ( $value === null ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is null, but non null value was expected.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function null($value, string $message = ''): void
    {
        if ( $value !== null ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not null, but null value was expected.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function numeric($value, string $message = ''): void
    {
        if ( ! \is_numeric($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not numeric.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function range($value, int $min, int $max, string $message = ''): void
    {
        if ( $value < $min || $value > $max ) {
            $message = \sprintf(
                $message ?: 'Number "%s" was expected to be at least "%d" and at most "%d".',
                $value, $min, $max
            );

            throw new ValidationFailedException($message, $value, [ 'min' => $min, 'max' => $max ]);
        }
    }

    /**
     * @param        $value
     * @param string $pattern
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function regex($value, string $pattern, string $message = ''): void
    {
        if ( ! \preg_match($pattern, $value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" does not match expression.',
                $value
            );

            throw new ValidationFailedException($message, $value, [ 'pattern' => $pattern ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function scalar($value, string $message = ''): void
    {
        if ( ! \is_scalar($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a scalar.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $needle
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function startsWith($value, string $needle, string $message = ''): void
    {
        if ( $value[0] !== $needle ) {
            $message = \sprintf(
                $message ?: 'Value "%s" does not ends with "%s".',
                $value, $needle
            );

            throw new ValidationFailedException($message, $value, [ 'needle' => $needle ]);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function string($value, string $message = ''): void
    {
        if ( ! \is_string($value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid string.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function url($value, string $message = ''): void
    {
        if ( ! \filter_var($value, FILTER_VALIDATE_URL) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid url.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function uuid($value, string $message = ''): void
    {
        $value = \str_replace([ 'urn:', 'uuid:', '{', '}' ], '', $value);
        if ( ! \preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/', $value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid uuid.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @throws ValidationFailedException
     */
    public function phone($value, string $message = ''): void
    {
        if ( ! \preg_match('/^\+?[1-9]\d{1,14}$/', $value) ) {
            $message = \sprintf(
                $message ?: 'Value "%s" is not a valid E164 number.',
                $value
            );

            throw new ValidationFailedException($message, $value);
        }
    }
}