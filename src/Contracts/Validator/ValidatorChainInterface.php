<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
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
interface ValidatorChainInterface
{

    /**
     *
     * @return ViolationInterface[]
     */
    public function getViolations(): array;

    /**
     *
     * @param array $data
     */
    public function verify(array $data): void;
}