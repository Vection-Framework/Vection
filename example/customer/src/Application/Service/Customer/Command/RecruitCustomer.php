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

namespace Apps\Customer\Application\Service\Customer\Command;


use Vection\Component\CQRS\Command\Command;
use Vection\Component\Validator\ValidationChain;
use Vection\Contracts\Validator\ValidationChainInterface;

/**
 * Class RecruitCustomer
 *
 * @package Apps\Customer\Application\Service\Customer\Command
 */
class RecruitCustomer extends Command
{
    public const FIRST_NAME = 'firstName';
    public const LAST_NAME  = 'lastName';

    /**
     * Defines constraints for a set of data.
     *
     * @param ValidationChain $chain
     */
    public function defineValidation(ValidationChainInterface $chain): void
    {
        $chain(self::FIRST_NAME)
            ->maxLength(10)
            ->string();

        $chain(self::LAST_NAME)
            ->maxLength(45)
            ->string();
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->payload->get(self::FIRST_NAME);
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->payload->get(self::LAST_NAME);
    }
}