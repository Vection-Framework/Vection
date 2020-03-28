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

namespace Vection\Component\Validator\Schema\Exception;

/**
 * Class MissingPropertyException
 *
 * @package Vection\Component\Validator\Schema\Exception
 *
 * @author David Lung <vection@davidlung.de>
 */
class MissingPropertyException extends PropertyException
{
    /**
     * IllegalTypeException constructor.
     *
     * @param string $property
     */
    public function __construct(string $property)
    {
        parent::__construct(sprintf('Missing property "%s".', $property));
        $this->property = $property;
    }

    /**
     * @inheritDoc
     */
    public function withProperty(string $property): void
    {
        throw new self($property.'.'.$this->property);
    }
}
