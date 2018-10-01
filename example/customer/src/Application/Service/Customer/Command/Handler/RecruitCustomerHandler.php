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

namespace Apps\Customer\Application\Service\Customer\Command\Handler;


use Apps\Customer\Application\Service\Customer\Command\RecruitCustomer;
use Apps\Customer\Domain\Customer\Entity\Customer;
use Apps\Customer\Domain\Customer\Event\CustomerRecruited;
use Apps\Customer\Domain\Customer\ValueObject\Firstname;
use Apps\Customer\Domain\Customer\ValueObject\Lastname;
use Apps\Customer\Persistence\Doctrine\Repository\CustomerRepository;
use AppsDock\Core\App\Application\CommandHandler;
use Vection\Component\DI\Annotations\Inject;

/**
 * Class RecruitCustomerHandler
 *
 * @package Apps\Customer\Application\Service\Customer\Command\Handler
 */
class RecruitCustomerHandler extends CommandHandler
{
    /**
     * @Inject('Apps\Customer\Domain\Customer\Entity\CustomerRepository')
     * @var CustomerRepository
     */
    private $customerRepository;

    /**
     * @param RecruitCustomer $message
     *
     * @throws \Exception
     */
    public function __invoke(RecruitCustomer $message)
    {

        $customer = new Customer(
            $this->customerRepository->nextId(),
            new Firstname($message->getFirstname()),
            new Lastname($message->getLastname())
        );

        $this->customerRepository->add($customer);
        $this->bus->publish(new CustomerRecruited($customer));
    }
}