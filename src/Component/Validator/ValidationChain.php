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

use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class ValidationChain
 *
 * @package Vection\Component\Validator
 */
class ValidationChain implements ValidationChainInterface
{
    /**
     * This property contains assertion definition.
     * Each entry can have multiple assertion with
     * different parameters.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Adds a key for which all following
     * assertion will be set.
     *
     * @param string $name
     *
     * @return ValidationChain
     */
    public function __invoke(string $name): ValidationChainInterface
    {
        $this->definitions[$name] = [];
        end($this->definitions);

        return $this;
    }

    /**
     * Returns all defined assertions.
     *
     * @return array
     */
    public function getDefinitions(): array
    {
        return $this->definitions;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function alnum(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['alnum'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @return ValidationChain
     */
    public function betweenValue(int $min, int $max, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['betweenValue'] = [
            'min' => $min, 'max' => $max, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @return ValidationChain
     */
    public function betweenLength(int $min, int $max, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['betweenLength'] = [
            'min' => $min, 'max' => $max, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function boolean(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['boolean'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param array  $needle
     * @param string $message
     *
     * @return ValidationChain
     */
    public function contains(array $needle, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['contains'] = [
            $needle, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param array  $list
     * @param string $message
     *
     * @return ValidationChain
     */
    public function choice(array $list, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['choice'] = [
            $list, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notEmpty(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notEmpty'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $format
     * @param string $message
     *
     * @return ValidationChain
     */
    public function date(string $format, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['date'] = [
            $format, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function digit(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['digit'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function directory(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['directory'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function email(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['email'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $char
     * @param string $message
     *
     * @return ValidationChain
     */
    public function endsWith(string $char, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['endsWith'] = [
            'char' => $char, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function false(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['false'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function true(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['true'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function file(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['file'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function greaterOrEqualThan(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['greaterOrEqualThan'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function greaterThan(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['greaterThan'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param array  $array
     * @param string $message
     *
     * @return ValidationChain
     */
    public function inArray(array $array, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['inArray'] = [
            $array, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function integer(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['integer'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function ip(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['ip'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function ipv4(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['ipv4'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function ipv6(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['ipv6'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function isArray(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['isArray'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function isJsonString(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['isJsonString'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $length
     * @param string $message
     *
     * @return ValidationChain
     */
    public function length(int $length, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['length'] = [
            $length, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function lessOrEqualThan(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['lessOrEqualThan'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function lessThan(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['lessThan'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $value
     * @param string $message
     *
     * @return ValidationChain
     */
    public function max(int $value, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['max'] = [
            $value, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $length
     * @param string $message
     *
     * @return ValidationChain
     */
    public function maxLength(int $length, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['maxLength'] = [
            $length, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function min(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['min'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $limit
     * @param string $message
     *
     * @return ValidationChain
     */
    public function minLength(int $limit, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['minLength'] = [
            $limit, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notBlank(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notBlank'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notEquals($value, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notEquals'] = [
            $value, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @return ValidationChain
     */
    public function equals($value, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['equals'] = [
            $value, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notSame($value, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notSame'] = [
            $value, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param        $value
     * @param string $message
     *
     * @return ValidationChain
     */
    public function same($value, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['same'] = [
            $value, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param array  $array
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notInArray(array $array, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notInArray'] = [
            $array, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function notNull(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['notNull'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function null(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['null'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function nullable(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['nullable'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function numeric(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['numeric'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param int    $min
     * @param int    $max
     * @param string $message
     *
     * @return ValidationChain
     */
    public function range(int $min, int $max, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['range'] = [
            'min' => $min, 'max' => $max, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $pattern
     * @param string $message
     *
     * @return ValidationChain
     */
    public function regex(string $pattern, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['regex'] = [
            $pattern, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function scalar(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['scalar'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $needle
     * @param string $message
     *
     * @return ValidationChain
     */
    public function startsWith(string $needle, string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['startsWith'] = [
            $needle, 'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function string(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['string'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function url(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['url'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function uuid(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['uuid'] = [
            'msg' => $message,
        ];

        return $this;
    }

    /**
     * @param string $message
     *
     * @return ValidationChain
     */
    public function phone(string $message = ''): ValidationChain
    {
        $this->definitions[\key($this->definitions)]['phone'] = [
            'msg' => $message,
        ];

        return $this;
    }
}