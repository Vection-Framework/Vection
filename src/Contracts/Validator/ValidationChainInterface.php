<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ValidationChainInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationChainInterface
{

    /**
     * Returns all defined assertions.
     *
     * @return array
     */
    public function getDeferredValidation(): array;

    /**
     * @param string $validatorClassName
     * @param array $constraints
     *
     * @return ValidationChainInterface
     */
    public function validate(string $validatorClassName, array $constraints = []): ValidationChainInterface;

    /**
     * Adds a key for which all following
     * assertion will be set.
     *
     * @param string $name
     *
     * @return ValidationChainInterface
     */
    public function __invoke(string $name): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function alnum(string $message = ''): ValidationChainInterface;

    /**
     * @param int $min
     * @param int $max
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function betweenValue(int $min, int $max, string $message = ''): ValidationChainInterface;

    /**
     * @param int $min
     * @param int $max
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function betweenLength(int $min, int $max, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function boolean(string $message = ''): ValidationChainInterface;

    /**
     * @param array $needle
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function contains(array $needle, string $message = ''): ValidationChainInterface;

    /**
     * @param array $list
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function choice(array $list, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notEmpty(string $message = ''): ValidationChainInterface;

    /**
     * @param string $format
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function date(string $format, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function digit(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function directory(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function email(string $message = ''): ValidationChainInterface;

    /**
     * @param string $char
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function endsWith(string $char, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function false(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function true(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function file(string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function greaterOrEqualThan(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function greaterThan(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param array $array
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function inArray(array $array, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function integer(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function ip(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function ipv4(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function ipv6(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function isArray(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function isJsonString(string $message = ''): ValidationChainInterface;

    /**
     * @param int $length
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function length(int $length, string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function lessOrEqualThan(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function lessThan(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param int $value
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function max(int $value, string $message = ''): ValidationChainInterface;

    /**
     * @param int $length
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function maxLength(int $length, string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function min(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param int $limit
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function minLength(int $limit, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notBlank(string $message = ''): ValidationChainInterface;

    /**
     * @param $value
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notEquals($value, string $message = ''): ValidationChainInterface;

    /**
     * @param $value
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function equals($value, string $message = ''): ValidationChainInterface;

    /**
     * @param $value
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notSame($value, string $message = ''): ValidationChainInterface;

    /**
     * @param $value
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function same($value, string $message = ''): ValidationChainInterface;

    /**
     * @param array $array
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notInArray(array $array, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function notNull(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function null(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function nullable(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function numeric(string $message = ''): ValidationChainInterface;

    /**
     * @param int $min
     * @param int $max
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function range(int $min, int $max, string $message = ''): ValidationChainInterface;

    /**
     * @param string $pattern
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function regex(string $pattern, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function scalar(string $message = ''): ValidationChainInterface;

    /**
     * @param string $needle
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function startsWith(string $needle, string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function string(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function url(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function uuid(string $message = ''): ValidationChainInterface;

    /**
     * @param string $message
     *
     * @return ValidationChainInterface
     */
    public function phone(string $message = ''): ValidationChainInterface;
}