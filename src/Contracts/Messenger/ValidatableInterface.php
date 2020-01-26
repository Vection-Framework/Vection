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

namespace Vection\Contracts\Messenger;

use Vection\Contracts\Validator\ValidatorChainInterface;

/**
 * Interface ValidatableInterface
 *
 * @package Vection\Contracts\Messenger\Payload
 *
 * @author  David Lung <vection@davidlung.de>
 */
interface ValidatableInterface
{
    /**
     * Returns an ValidationChain object that defines the validators
     * for the data which will be validated with.
     *
     * @return ValidatorChainInterface
     */
    public function getValidationChain(): ValidatorChainInterface;

    /**
     * Returns an array that contains the data which will be validated.
     *
     * @return array
     */
    public function getValidatableData(): array;
}
