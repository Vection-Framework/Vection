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

namespace Apps\Customer\Persistence\Doctrine\Repository;


use Apps\Customer\Domain\Customer\Repository\CustomerRepositoryInterface;
use Apps\Customer\Domain\Customer\ValueObject\CustomerId;
use AppsDock\Core\App\Persistence\Repository;
use AppsDock\Core\Common\Domain\ValueObject\UuidAdapter;

/**
 * Class CustomerRepository
 *
 * @package Apps\Customer\Persistence\Doctrine\Repository
 */
class CustomerRepository extends Repository implements CustomerRepositoryInterface
{

    /**
     * @return CustomerId
     * @throws \Exception
     */
    public function nextId(): CustomerId
    {
        $uuid = UuidAdapter::generate()->toString();
        return new CustomerId($uuid);
    }
}