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

namespace Apps\Customer\Domain\Customer\Entity;


use Apps\Customer\Domain\Customer\Exception\CustomerException;
use Apps\Customer\Domain\Customer\ValueObject\CustomerId;
use Apps\Customer\Domain\Customer\ValueObject\Firstname;
use Apps\Customer\Domain\Customer\ValueObject\Lastname;
use AppsDock\Core\App\Domain\AggregateRoot;

/**
 * Class Customer
 *
 * @package Apps\Customer\Domain\Customer\Entity
 */
class Customer extends AggregateRoot
{
    /** @var CustomerId */
    private $customerId;
    /** @var Firstname */
    private $firstname;
    /** @var Lastname */
    private $lastname;
    /** @var bool */
    private $banned;

    /**
     * Customer constructor.
     *
     * @param CustomerId $aCustomerId
     * @param Firstname  $aFirstname
     * @param Lastname   $aLastname
     */
    public function __construct(
        CustomerId $aCustomerId,
        Firstname $aFirstname,
        Lastname $aLastname)
    {
        $this->setCustomerId($aCustomerId);
        $this->setFirstname($aFirstname);
        $this->setLastname($aLastname);
    }

    /**
     * @throws CustomerException
     */
    public function recruitCustomer()
    {
        CustomerException::isBanned($this);
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->banned;
    }

    //region DEFINE PUBLIC GETTERS HERE

    /**
     * @return string
     */
    public function customerId(): string
    {
        return $this->customerId;
    }

    //endregion

    //region DEFINE PRIVATE SETTERS HERE

    /**
     * @param CustomerId $customerId
     */
    private function setCustomerId(CustomerId $customerId): void
    {
        $this->customerId = $customerId;
    }

    /**
     * @param Firstname $firstname
     */
    private function setFirstname(Firstname $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @param Lastname $lastname
     */
    private function setLastname(Lastname $lastname): void
    {
        $this->lastname = $lastname;
    }

    //endregion
}