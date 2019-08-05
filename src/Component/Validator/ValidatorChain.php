<?php declare(strict_types=1);

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

use Vection\Contracts\Validator\ValidatorChainInterface;
use Vection\Contracts\Validator\ViolationInterface;

/**
 * Class ValidationChain
 *
 * @package Vection\Component\Validator
 *
 * @method ValidatorChain alphaNumeric()
 * @method ValidatorChain betweenValue(int $min, int $max)
 * @method ValidatorChain betweenLength(int $min, int $max)
 * @method ValidatorChain boolean()
 * @method ValidatorChain contains(array $needle)
 * @method ValidatorChain choice(array $list)
 * @method ValidatorChain notEmpty()
 * @method ValidatorChain date(string $format)
 * @method ValidatorChain digit()
 * @method ValidatorChain directory()
 * @method ValidatorChain email()
 * @method ValidatorChain endsWith(string $char)
 * @method ValidatorChain false()
 * @method ValidatorChain true()
 * @method ValidatorChain file()
 * @method ValidatorChain greaterOrEqualThan(int $limit)
 * @method ValidatorChain greaterThan(int $limit)
 * @method ValidatorChain inArray(array $array)
 * @method ValidatorChain integer()
 * @method ValidatorChain ip()
 * @method ValidatorChain ipv4()
 * @method ValidatorChain ipv6()
 * @method ValidatorChain isArray()
 * @method ValidatorChain isJsonString()
 * @method ValidatorChain length(int $length)
 * @method ValidatorChain lessOrEqualThan(int $limit)
 * @method ValidatorChain lessThan(int $limit)
 * @method ValidatorChain max(int $value)
 * @method ValidatorChain maxLength(int $length)
 * @method ValidatorChain min(int $limit)
 * @method ValidatorChain minLength(int $limit)
 * @method ValidatorChain notBlank()
 * @method ValidatorChain notEquals($value)
 * @method ValidatorChain equals($value)
 * @method ValidatorChain notSame($value)
 * @method ValidatorChain same($value)
 * @method ValidatorChain notInArray(array $array)
 * @method ValidatorChain notNull()
 * @method ValidatorChain null()
 * @method ValidatorChain nullable()
 * @method ValidatorChain numeric()
 * @method ValidatorChain range(int $min, int $max)
 * @method ValidatorChain regex(string $pattern)
 * @method ValidatorChain scalar()
 * @method ValidatorChain startsWith(string $needle)
 * @method ValidatorChain string()
 * @method ValidatorChain url()
 * @method ValidatorChain uuid()
 * @method ValidatorChain phone()
 */
class ValidatorChain implements ValidatorChainInterface
{
    /**
     * This property contains assertion definition.
     * Each entry can have multiple assertion with
     * different parameters.
     *
     * @var Validator[][]
     */
    protected $chain = [];

    /**
     *
     * @var ViolationInterface[]
     */
    protected $violations = [];

    /**
     * @var int[]
     */
    protected $nullable = [];

    /**
     * Adds a key for which all following
     * assertion will be set.
     *
     * @param string $name
     *
     * @return ValidatorChain
     */
    public function __invoke(string $name): ValidatorChain
    {
        $this->chain[$name] = null;
        end($this->chain);
        return $this;
    }

    /**
     * This magic method is used to accept several validation
     * function which will be process later in the Validator.
     *
     * @param string $name
     * @param array $constraints|null
     *
     * @return ValidatorChain
     */
    public function __call($name, $constraints = []): ValidatorChain
    {
        if( $name === 'nullable' ){
            $this->nullable[key($this->chain)] = 1;
            return $this;
        }

        $validatorClass = __NAMESPACE__ .'\\Validator\\'. ucfirst($name);
        $this->chain[key($this->chain)][] = new $validatorClass(...$constraints);

        return $this;
    }

    /**
     *
     * @return ViolationInterface[]
     */
    public function getViolations(): array
    {
        return $this->violations;
    }

    /**
     * @param array $data
     */
    public function verify(array $data): void
    {
        foreach( $this->chain as $subject => $validators ){

            $value = \array_key_exists($subject, $data) ? $data[$subject] : null;

            # Skip if value is null and validate against nullable
            if( $value === null && isset($this->nullable[$subject]) ){
                continue;
            }

            foreach( $validators as $validator ){
                if( $violation = $validator->validate($value) ){
                    $this->violations[$subject] = $violation;
                    continue 2;
                }
            }

        }
    }

}