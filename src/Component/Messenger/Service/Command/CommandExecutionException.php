<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Vection\Component\Messenger\Service\Command;

use Exception;
use Vection\Contracts\Messenger\Service\Command\CommandExecutionExceptionInterface;

/**
 * Class CommandExecutionException
 *
 * @package Vection\Component\Messenger\Service\Command
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class CommandExecutionException extends Exception implements CommandExecutionExceptionInterface
{

}