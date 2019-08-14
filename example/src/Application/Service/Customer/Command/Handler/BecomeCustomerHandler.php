<?php
/**
 * This file is part of the AppsDock project.
 * Visit project at https://www.appsdock.de
 *
 * (c) AppsDock <project@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Example\Application\Service\Customer\Command\Handler;


use AppsDock\Core\App\Application\CommandHandler;
use Example\Application\Service\Customer\Command\BecomeCustomer;

/**
 * Class CreateCustomerHandler
 *
 * @package Example\Application\Service\Customer\Command\Handler
 */
class BecomeCustomerHandler extends CommandHandler
{

    public function __invoke(BecomeCustomer $message)
    {

    }

}