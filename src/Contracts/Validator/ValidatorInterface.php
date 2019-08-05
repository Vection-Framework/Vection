<?php declare(strict_types=1);

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 *  (c) Vection <project@vection.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Contracts\Validator;

/**
 * Interface ValidatorInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ValidatorInterface
{
    /**
     * @param $value
     *
     * @return ViolationInterface|null
     */
    public function validate($value): ? ViolationInterface;

}