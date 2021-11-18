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

namespace Vection\Component\Validator\Schema\Exception;

/**
 * Class InvalidPropertyValueException
 *
 * @package Vection\Component\Validator\Schema\Exception
 *
 * @author David Lung <vection@davidlung.de>
 */
class InvalidPropertyValueException extends PropertyException
{

    /**
     * @var string
     */
    protected $details;

    /**
     * @var string
     */
    protected $rawMessage;

    /**
     * IllegalPropertyException constructor.
     *
     * @param string      $property
     * @param string      $message
     */
    public function __construct(string $property, string $message)
    {
        parent::__construct(sprintf($message, $property));

        $this->rawMessage = $message;
        $this->property   = $property;
    }

    /**
     * @inheritDoc
     */
    public function withProperty(string $property): void
    {
        throw new self($property.'.'.$this->property, $this->rawMessage);
    }
}
