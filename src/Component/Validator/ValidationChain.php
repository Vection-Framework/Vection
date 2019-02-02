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

namespace Vection\Component\Validator;

use Vection\Component\Validator\Validator\GeneralValidator;
use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class ValidationChain
 *
 * @package Vection\Component\Validator
 *
 * @method ValidationChain alnum(string $message = '')
 * @method ValidationChain betweenValue(int $min, int $max, string $message = '')
 * @method ValidationChain betweenLength(int $min, int $max, string $message = '')
 * @method ValidationChain boolean(string $message = '')
 * @method ValidationChain contains(array $needle, string $message = '')
 * @method ValidationChain choice(array $list, string $message = '')
 * @method ValidationChain notEmpty(string $message = '')
 * @method ValidationChain date(string $format, string $message = '')
 * @method ValidationChain digit(string $message = '')
 * @method ValidationChain directory(string $message = '')
 * @method ValidationChain email(string $message = '')
 * @method ValidationChain endsWith(string $char, string $message = '')
 * @method ValidationChain false(string $message = '')
 * @method ValidationChain true(string $message = '')
 * @method ValidationChain file(string $message = '')
 * @method ValidationChain greaterOrEqualThan(int $limit, string $message = '')
 * @method ValidationChain greaterThan(int $limit, string $message = '')
 * @method ValidationChain inArray(array $array, string $message = '')
 * @method ValidationChain integer(string $message = '')
 * @method ValidationChain ip(string $message = '')
 * @method ValidationChain ipv4(string $message = '')
 * @method ValidationChain ipv6(string $message = '')
 * @method ValidationChain isArray(string $message = '')
 * @method ValidationChain isJsonString(string $message = '')
 * @method ValidationChain length(int $length, string $message = '')
 * @method ValidationChain lessOrEqualThan(int $limit, string $message = '')
 * @method ValidationChain lessThan(int $limit, string $message = '')
 * @method ValidationChain max(int $value, string $message = '')
 * @method ValidationChain maxLength(int $length, string $message = '')
 * @method ValidationChain min(int $limit, string $message = '')
 * @method ValidationChain minLength(int $limit, string $message = '')
 * @method ValidationChain notBlank(string $message = '')
 * @method ValidationChain notEquals($value, string $message = '')
 * @method ValidationChain equals($value, string $message = '')
 * @method ValidationChain notSame($value, string $message = '')
 * @method ValidationChain same($value, string $message = '')
 * @method ValidationChain notInArray(array $array, string $message = '')
 * @method ValidationChain notNull(string $message = '')
 * @method ValidationChain null(string $message = '')
 * @method ValidationChain nullable(string $message = '')
 * @method ValidationChain numeric(string $message = '')
 * @method ValidationChain range(int $min, int $max, string $message = '')
 * @method ValidationChain regex(string $pattern, string $message = '')
 * @method ValidationChain scalar(string $message = '')
 * @method ValidationChain startsWith(string $needle, string $message = '')
 * @method ValidationChain string(string $message = '')
 * @method ValidationChain url(string $message = '')
 * @method ValidationChain uuid(string $message = '')
 * @method ValidationChain phone(string $message = '')
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
    protected $validation = [];

    /**
     * Returns all defined assertions.
     * The definition array consists of the
     *
     * @return array
     */
    public function getDeferredValidation(): array
    {
        return $this->validation;
    }

    /**
     * @param string $validatorClassName
     * @param array $constraints
     *
     * @return ValidationChainInterface
     */
    public function validate(string $validatorClassName, array $constraints = []): ValidationChainInterface
    {
        $this->validation[\key($this->validation)][$validatorClassName][] = $constraints;

        return $this;
    }

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
        $this->validation[$name] = [];
        end($this->validation);

        return $this;
    }

    /**
     * This magic method is used to accept several validation
     * function which will be process later in the Validator.
     *
     * @param string $name
     * @param array $constraints
     *
     * @return ValidationChainInterface
     */
    public function __call($name, $constraints): ValidationChainInterface
    {
        \array_unshift($constraints, $name);
        return $this->validate(GeneralValidator::class, $constraints);
    }

}