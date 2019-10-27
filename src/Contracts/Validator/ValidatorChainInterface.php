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

/*
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ValidationChainInterface
 *
 * Builds a chain of validator instances.
 * Each validation method creates an specific instance of a validator.
 * This validator chain provides the possibility to validate one or more
 * values by multiple validators at once.
 *
 * @package Vection\Contracts\Validator
 */
interface ValidatorChainInterface
{
    /**
     * Returns all violation objects collected during the verify method.
     *
     * @return ViolationInterface[]
     */
    public function getViolations(): array;

    /**
     * Verifies the given data against the chain of all registered validators.
     * The validation process does not stop on failed validations to collect violation
     * information of the whole given data. After the verification of the values you can use the
     * ValidatorChainInterface::getViolations() method that returns all occurred violations or an empty
     * array if the were no violations of validation rules.
     *
     * @param array $data
     */
    public function verify(array $data): void;
}
