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
 * @method alnum(string $message = '')
 * @method betweenValue(int $min, int $max, string $message = '')
 * @method betweenLength(int $min, int $max, string $message = '')
 * @method boolean(string $message = '')
 * @method contains(array $needle, string $message = '')
 * @method choice(array $list, string $message = '')
 * @method notEmpty(string $message = '')
 * @method date(string $format, string $message = '')
 * @method digit(string $message = '')
 * @method directory(string $message = '')
 * @method email(string $message = '')
 * @method endsWith(string $char, string $message = '')
 * @method false(string $message = '')
 * @method true(string $message = '')
 * @method file(string $message = '')
 * @method greaterOrEqualThan(int $limit, string $message = '')
 * @method greaterThan(int $limit, string $message = '')
 * @method inArray(array $array, string $message = '')
 * @method integer(string $message = '')
 * @method ip(string $message = '')
 * @method ipv4(string $message = '')
 * @method ipv6(string $message = '')
 * @method isArray(string $message = '')
 * @method isJsonString(string $message = '')
 * @method length(int $length, string $message = '')
 * @method lessOrEqualThan(int $limit, string $message = '')
 * @method lessThan(int $limit, string $message = '')
 * @method max(int $value, string $message = '')
 * @method maxLength(int $length, string $message = '')
 * @method min(int $limit, string $message = '')
 * @method minLength(int $limit, string $message = '')
 * @method notBlank(string $message = '')
 * @method notEquals($value, string $message = '')
 * @method equals($value, string $message = '')
 * @method notSame($value, string $message = '')
 * @method same($value, string $message = '')
 * @method notInArray(array $array, string $message = '')
 * @method notNull(string $message = '')
 * @method null(string $message = '')
 * @method nullable(string $message = '')
 * @method numeric(string $message = '')
 * @method range(int $min, int $max, string $message = '')
 * @method regex(string $pattern, string $message = '')
 * @method scalar(string $message = '')
 * @method startsWith(string $needle, string $message = '')
 * @method string(string $message = '')
 * @method url(string $message = '')
 * @method uuid(string $message = '')
 * @method phone(string $message = '')
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