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
 * Class Lastname
 *
 * @package Example\Domain\Customer\ValueObject
 */
final class Lastname
{
    /**
     * @var string
     */
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
            throw new \Exception('Lastname not valid');
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