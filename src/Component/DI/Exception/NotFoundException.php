<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\DI\Exception;

use Psr\Container\NotFoundExceptionInterface;

/**
 * Class NotFoundException
 *
 * @package Vection\Component\DI\Exception
 */
class NotFoundException extends \RuntimeException implements NotFoundExceptionInterface
{

}