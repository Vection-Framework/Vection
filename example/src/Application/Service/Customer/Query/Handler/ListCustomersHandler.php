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

namespace Example\Application\Service\Customer\Query\Handler;


use AppsDock\Core\App\Application\QueryHandler;
use Example\Application\Service\Customer\Query\ListCustomers;
use Vection\Contracts\MessageBus\Query\QueryInterface;
use Vection\Contracts\MessageBus\Query\ReadModelInterface;

/**
 * Class GetCustomersHandler
 *
 * @package Example\Application\Service\Customer\Query\Handler
 */
class ListCustomersHandler extends QueryHandler
{

    public function __invoke(ListCustomers $message )
    {

    }
}