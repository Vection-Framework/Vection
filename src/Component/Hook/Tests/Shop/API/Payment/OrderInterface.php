<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Hook\Tests\Shop\API\Payment;

/**
 * Interface OrderInterface
 *
 * @package Vection\Component\Hook\Tests\Shop\API\Payment
 */
interface OrderInterface
{
    /**
     *
     * @return string
     */
    public function getProductName(): string;
}