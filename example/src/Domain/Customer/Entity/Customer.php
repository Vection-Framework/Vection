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

namespace Example\Domain\Customer\Entity;


use AppsDock\Core\Common\Domain\Collection;
use Example\Domain\Customer\Exception\CustomerException;
use Example\Domain\Customer\ValueObject\CustomerId;
use Example\Domain\Customer\ValueObject\Firstname;
use Example\Domain\Customer\ValueObject\Lastname;
use AppsDock\Core\App\Domain\AggregateRoot;
use AppsDock\Core\App\Domain\Identity;

/**
 * Class Customer
 *
 * @package AppsDock\Example\Domain\Customer\Entity
 */
class Customer extends AggregateRoot
{
    /** @var string Mapping name */
    public const ID = 'customer_id';
    /** @var string Database column name */
    public const ID_COL = 'id';
    /** @var string Mapping name */
    public const NO = 'customer_no';
    /** @var string Database column name */
    public const NO_COL = 'no';
    /** @var string Mapping name */
    public const FORMOFADDRESS = 'customer_form_of_address';
    /** @var string Database column name */
    public const FORMOFADDRESS_COL = 'formOfAddress';
    /** @var string Mapping name */
    public const FIRSTNAME = 'customer_first_name';
    /** @var string Database column name */
    public const FIRSTNAME_COL = 'firstName';
    /** @var string Mapping name */
    public const LASTNAME = 'customer_last_name';
    /** @var string Database column name */
    public const LASTNAME_COL = 'lastName';
    /** @var string Mapping name */
    public const EMAIL = 'customer_email';
    /** @var string Database column name */
    public const EMAIL_COL = 'email';

    /** @var string */
    private $formOfAddress;
    /** @var string */
    private $firstName;
    /** @var string */
    private $lastName;
    /** @var string */
    private $no;
    /** @var string */
    private $email;
    /** @var Collection  */
    protected $addresses;
    /** @var Collection */
    protected $phonenumbers;

    /**
     * Customer constructor.
     *
     * @param CustomerId $id
     * @param string     $formOfAddress
     * @param string     $firstName
     * @param string     $lastName
     * @param string     $no
     * @param string     $email
     */
    public function __construct(
        CustomerId $id,
        string $formOfAddress,
        string $firstName,
        string $lastName,
        string $no,
        string $email)
    {
        $this->setIdentity($id);

        $this->formOfAddress = $formOfAddress;
        $this->firstName     = $firstName;
        $this->lastName      = $lastName;
        $this->no            = $no;
        $this->email         = $email;
        $this->addresses     = new Collection();
        $this->phonenumbers  = new Collection();
    }

    /**
     * @param Address $address
     */
    public function addAddress(Address $address): void
    {
        $this->addresses->add($address);
    }

    /**
     * @param Phonenumber $phonenumber
     */
    public function addPhonenumbers(Phonenumber $phonenumber): void
    {
        $this->phonenumbers->add($phonenumber);
    }

    //region -> GETTERS

    /**
     * @return Identity
     */
    public function getIdentity(): CustomerId
    {
        return new CustomerId($this->identity);
    }

    /**
     * @return string
     */
    public function getFormOfAddress(): string
    {
        return $this->formOfAddress;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    //endregion
}