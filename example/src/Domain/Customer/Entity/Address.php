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

namespace Example\Domain\Customer\Entity;


use AppsDock\Core\App\Domain\Entity;
use AppsDock\Core\Common\Domain\ValueObject\AddressTrait;

/**
 * Class BillingAddress
 *
 * @package Example\Domain\Customer\Entity
 */
class Address extends Entity
{
    use AddressTrait { AddressTrait::construct as private construct; }

    /** @var string */
    protected $type;

    /**
     * Address constructor.
     *
     * @param string $street
     * @param string $postalCode
     * @param string $city
     * @param string $country
     * @param string $type
     */
    public function __construct(
        string $street,
        string $postalCode,
        string $city,
        string $country,
        string $type
    )
    {
        $this->construct($street, $postalCode, $city, $country);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}