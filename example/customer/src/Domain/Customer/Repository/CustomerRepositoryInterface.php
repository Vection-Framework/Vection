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

namespace Apps\Customer\Domain\Customer\Repository;


use Apps\Customer\Domain\Customer\ValueObject\CustomerId;

/**
 * Class CustomerRepositoryInterface
 *
 * @package Apps\Customer\Domain\Customer\Repository
 */
interface CustomerRepositoryInterface
{
    /**
     * @return CustomerId
     */
    public function nextId(): CustomerId;
}