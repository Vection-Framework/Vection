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
use ReflectionException;
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
     * @throws ReflectionException
     */
    public function create(string $name, array $constraints = []): ValidatorInterface
    {
        if (class_exists($name)) {
            $validatorClassName = $name;

            if (!in_array(ValidatorInterface::class, class_implements($name), true)) {
                throw new RuntimeException('Validator does not implement Validator interface');
            }
        }
        else {
            $validatorClassName = __NAMESPACE__ .'\\Validator\\'. ucfirst($name);

            if (!class_exists($validatorClassName)) {
                throw new RuntimeException('Validator does not exists - '.$validatorClassName);
            }
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
