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

namespace Example\Application\Service\Customer\Command;


use Vection\Component\MessageBus\Command\Command;

/**
 * Class GenericCustomer
 *
 * @package Example\Application\Service\Customer\Command
 */
class GenericCustomer extends Command
{
    public const TITLE       = 'title';
    public const FIRST_NAME  = 'firstName';
    public const LAST_NAME   = 'lastName';
    public const EMAIL       = 'email';

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->get(self::TITLE);
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->get(self::FIRST_NAME);
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->get(self::LAST_NAME);
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->get(self::EMAIL);
    }

    /**
     * @param string
     */
    public function setEmail(string $email): void
    {
        $this->set(self::EMAIL, $email);
    }
}