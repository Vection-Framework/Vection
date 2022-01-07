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

namespace Vection\Component\Validator\Schema\Property;

use Vection\Component\Validator\Schema\Exception\IllegalPropertyTypeException;
use Vection\Component\Validator\Schema\Exception\IllegalPropertyValueException;
use Vection\Component\Validator\Schema\Property;
use Vection\Component\Validator\Schema\Property\Traits\FloatPropertyTrait;

/**
 * Class FloatProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 * @author  David Lung <vection@davidlung.de>
 */
class FloatProperty extends Property
{
    use FloatPropertyTrait;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        $this->evaluateFloatProperty($schema);
    }

    /**
     * @inheritDoc
     *
     * @throws IllegalPropertyTypeException
     * @throws IllegalPropertyValueException
     */
    public function onValidate($value): void
    {
        $this->validateFloatProperty($value);
    }
}
