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
use Vection\Component\Validator\Schema\Property\Traits\ObjectPropertyTrait;

/**
 * Class ObjectProperty
 *
 * @package Vection\Component\Validator\Schema\Property
 *
 * @author David Lung <vection@davidlung.de>
 */
class ObjectProperty extends Property
{
    use ObjectPropertyTrait;

    /** @var Property[] */
    protected array     $properties = [];
    protected ?Property $property   = null;

    /**
     * @inheritDoc
     */
    protected function onEvaluate(array $schema): void
    {
        $this->evaluateObjectProperty($schema);
    }

    /**
     * @inheritDoc
     */
    public function onValidate($values): void
    {
        $this->validateObjectProperty($values);
    }
}
