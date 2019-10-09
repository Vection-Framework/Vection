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

declare(strict_types = 1);

namespace Vection\Component\Validator\Schema\Json\Exception;

use Exception;
use Vection\Contracts\Validator\Schema\Json\JsonPropertyExceptionInterface;

/**
 * Class JsonPropertyException
 *
 * @package Vection\Component\Validator\Schema\Json\Exception
 *
 * @author David Lung <vection@davidlung.de>
 */
abstract class JsonPropertyException extends Exception implements JsonPropertyExceptionInterface
{
    /**
     * @var string
     */
    protected $property;
}