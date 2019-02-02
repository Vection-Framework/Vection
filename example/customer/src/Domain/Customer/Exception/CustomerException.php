<?php
/**
 *
 * This file is part of the AppsDock project.
 * Visit project at https://www.appsdock.de
 *
 * (c) AppsDock <project@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 */

namespace Apps\Customer\Domain\Customer\Exception;


use Apps\Customer\Domain\Customer\Entity\Customer;

/**
 * Class CustomerException
 *
 * @package Apps\Customer\Domain\Customer\Exception
 */
class CustomerException extends \Exception
{
    /**
     * @param Customer $aCustomer
     *
     * @throws CustomerException
     */
    public static function isBanned(Customer $aCustomer)
    {
        if ($aCustomer->isBanned()){
            throw new static(_('Customer was banned'));
        }
    }
}