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

namespace Vection\Component\Hook\Tests\Plugins\PayPal;

use Vection\Component\Hook\Tests\PaymentInterface;
use Vection\Component\Hook\Tests\Shop\API\Payment\OrderInterface;
use Vection\Component\Hook\Tests\Shop\API\Payment\TransactionInterface;

/**
 * Class PayPal
 *
 * @package Vection\Component\Hook\Tests\Plugins\Awesome
 */
class PayPal implements PaymentInterface
{

    /**
     * @param OrderInterface $order
     *
     * @return TransactionInterface
     */
    public function doPay(OrderInterface $order): TransactionInterface
    {
        $name = $order->getProductName();

        # do sum stuff

        $transaction = new Transaction();

        return $transaction;
    }
}