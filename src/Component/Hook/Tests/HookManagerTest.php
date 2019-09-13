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

namespace Vection\Component\Hook\Tests;

use PHPUnit\Framework\TestCase;
use Vection\Component\Hook\Exception\HookException;
use Vection\Component\Hook\HookManager;

/**
 * Class HookManagerTest
 *
 * @package Vection\Component\Hook\Tests
 */
class HookManagerTest extends TestCase
{

    /**
     * @throws HookException
     */
    public function testHooks(){

        #$hookManager = new HookManager();

        #$hookManager->loadConfig(__DIR__.'/hook-config.json');


        #$loader = $hookManager->getLoader('plugin', 'vection.shop.payment');

        #$contextList = $loader->getContextList();
#
        ## Get by taking one from list or value from database / user input
        #$hookName = 'org.awesome.vection.payment.paypal';
#
        #/** @var PaymentInterface $payment */
        #$payment = $loader->load($hookName);
#
        #$order = new Order();
        #$transaction = $payment->doPay($order);

        $this->assertTrue(true);
    }


}
