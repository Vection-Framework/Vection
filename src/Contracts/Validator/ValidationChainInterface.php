<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.org
 *
 * (c) Vection <project@vection.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ValidationChainInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidationChainInterface
{
    /**
     * Adds a key for which all following
     * assertion will be set.
     *
     * @param string $name
     *
     * @return ValidationChainInterface
     */
    public function __invoke(string $name): ValidationChainInterface;

    /**
     * Returns all defined assertions.
     *
     * @return array
     */
    public function getDefinitions(): array;
}