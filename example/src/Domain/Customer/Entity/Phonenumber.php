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

/**
 * Class Phonenumber
 *
 * @package Example\Domain\Customer\Entity
 * @NonIdentity('Customer')
 */
class Phonenumber extends Entity
{
    /** @var int */
    protected $number;

    /**
     * Phonenumber constructor.
     *
     * @param $number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }


}