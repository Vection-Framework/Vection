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

namespace Example\Domain\Customer\Repository;


use Example\Domain\Customer\ValueObject\CustomerId;

/**
 * Class CustomerRepositoryInterface
 *
 * @package Example\Domain\Customer\Repository
 */
interface CustomerRepositoryInterface
{
    /**
     * @return CustomerId
     */
    public function nextId(): CustomerId;
}