<?php declare(strict_types=1);

/**
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
 * Interface ViolationInterface
 *
 * @package Vection\Contracts\Validator
 */
interface ViolationInterface
{
    /**
     *
     * @return mixed
     */
    public function getValue();

    /**
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     *
     * @return string
     */
    public function __toString(): string;
}