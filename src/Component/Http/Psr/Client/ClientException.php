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

namespace Vection\Component\Http\Psr\Client;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Class ClientException
 *
 * @package Vection\Component\Http\Psr\Client
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ClientException extends Exception implements ClientExceptionInterface
{

}