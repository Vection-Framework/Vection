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

use Vection\Component\Validator\Schema\Property;
use Vection\Component\Validator\Schema\Property\Traits\ArrayPropertyTrait;

/**
 * Class ArrayProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 * @author  David Lung <vection@davidlung.de>
 */
class ArrayProperty extends Property
{
    use ArrayPropertyTrait;

    protected Property $property;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        $this->evaluateArrayProperty($schema);
    }

    /**
     * @inheritDoc
     */
    public function onValidate($values): void
    {
        $this->validateArrayProperty($values);
    }
}
