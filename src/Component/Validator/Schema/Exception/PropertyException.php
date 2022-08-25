<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Vection-Framework <vection@appsdock.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Validator\Schema\Exception;

use Exception;
use Vection\Contracts\Validator\Schema\PropertyExceptionInterface;

/**
 * Class PropertyException
 *
 * @package Vection\Component\Validator\Schema\Exception
 * @author  David Lung <vection@davidlung.de>
 */
abstract class PropertyException extends Exception implements PropertyExceptionInterface
{
    protected string $property;
}
