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

declare(strict_types = 1);

namespace Vection\Component\Validator\Schema\Json\Exception;

/**
 * Class IllegalPropertyTypeException
 *
 * @package Vection\Component\Validator\Schema\Json\Exception
 *
 * @author David Lung <vection@davidlung.de>
 */
class IllegalPropertyTypeException extends IllegalPropertyException
{
    /**
     * IllegalPropertyTypeException constructor.
     *
     * @param string $property
     * @param string $expected
     */
    public function __construct(string $property, string $expected)
    {
        parent::__construct($property, 'The property "%s" expects a value of type %s.', $expected);
    }

    /**
     * @inheritDoc
     */
    public function withProperty(string $property): void
    {
        throw new static($property.'.'.$this->property, $this->details);
    }
}