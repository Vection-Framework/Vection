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

namespace Apps\Customer\Domain\Customer\ValueObject;


/**
 * Class Lastname
 *
 * @package Apps\Customer\Domain\Customer\ValueObject
 */
class Lastname
{
    private $lastname;

    /**
     * Lastname constructor.
     *
     * @param string $aLastname
     *
     * @throws \Exception
     */
    public function __construct(string $aLastname)
    {
        $this->setLastname($aLastname);
    }

    /**
     * @param string $aLastname
     *
     * @throws \Exception
     */
    public function validate(string $aLastname)
    {
        if ( false === strpos('mann',$aLastname) ){
            throw new \Exception(_('Lastname not valid'));
        }
    }

    /**
     * @param string $aLastname
     *
     * @throws \Exception
     */
    private function setLastname(string $aLastname): void
    {
        $this->validate($aLastname);

        $this->lastname = $aLastname;
    }
}