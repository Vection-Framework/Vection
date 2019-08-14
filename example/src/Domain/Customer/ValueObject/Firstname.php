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

namespace Example\Domain\Customer\ValueObject;


/**
 * Class Firstname
 *
 * @package Example\Domain\Customer\ValueObject
 */
final class Firstname
{
    private $firstname;

    /**
     * Firstname constructor.
     *
     * @param string $aFirstname
     *
     * @throws \Exception
     */
    public function __construct(string $aFirstname)
    {
        $this->setFirstname($aFirstname);
    }

    /**
     * @param string $aFirstname
     *
     * @throws \Exception
     */
    public function validate(string $aFirstname)
    {
        if ( ! in_array($aFirstname, ['Bernd','Volker','Simon'], true) ){
            throw new \Exception('Firstname not valid');
        }
    }

    /**
     * @param string $aFirstname
     *
     * @throws \Exception
     */
    private function setFirstname(string $aFirstname): void
    {
        $this->validate($aFirstname);

        $this->firstname = $aFirstname;
    }
}