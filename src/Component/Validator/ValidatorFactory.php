<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator;

use ReflectionClass;
use RuntimeException;
use Vection\Contracts\Validator\ValidatorInterface;

/**
 * Class ValidatorFactory
 *
 * @package Vection\Component\Validator
 * @author  David Lung <vection@davidlung.de>
 */
class ValidatorFactory
{
    /**
     * @param string $name
     * @param array  $constraints
     *
     * @return ValidatorInterface
     */
    public function create(string $name, array $constraints = []): ValidatorInterface
    {
        $validatorClassName = __NAMESPACE__ .'\\Validator\\'. ucfirst($name);

        if (!class_exists($validatorClassName)) {
            throw new RuntimeException('Validator does not exists - '.$validatorClassName);
        }

        if (is_int(array_key_first($constraints))) {
            $parameters = array_values($constraints);
        } else {
            $parameters = [];
            if ($constructor = (new ReflectionClass($validatorClassName))->getConstructor()) {
                foreach ($constructor->getParameters() as $constructorParameter) {
                    if (array_key_exists($constructorParameter->name, $constraints)) {
                        $parameters[] = $constraints[$constructorParameter->name];
                    }
                }
            }
        }

        return new $validatorClassName(...$parameters);
    }
}
