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

namespace Apps\Customer\Presentation\Rest;


use AppsDock\Core\App\Presentation\RestResource;
use Example\Application\Service\Customer\Command\BecomeCustomer;
use Example\Application\Service\Customer\Command\ModifyCustomer;
use Example\Application\Service\Customer\Query\ListCustomers;

/**
 * Class CustomerResource
 *
 * @package Apps\Customer\Presentation\Rest
 */
class CustomerResource extends RestResource
{
    /**
     * List all customer
     */
    public function list(): void
    {
        $query = new ListCustomers();

        $customers = $this->executeQuery($query);

        if ($customers !== null) {
            $this->responseData($customers);
        }
    }


    /**
     * Get customer
     */
    public function get(): void
    {
    }


    /**
     * Create customer
     */
    public function create(): void
    {
        $payload = $this->request->getJsonAsPayload();

        $message = new BecomeCustomer($payload);

        $this->executeCommand($message);

        $this->responseCreated($message->getIdentity()->getId());
    }


    /**
     * Update customer
     */
    public function update(): void
    {
        $payload = $this->request->getJsonAsPayload();

        $param = $this->request->getPathParam('0');

        $message = new ModifyCustomer($payload, $param);

        $this->executeCommand($message);

        $this->responseStatus(200);
    }

}
