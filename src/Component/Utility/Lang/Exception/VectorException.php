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

namespace Vection\Component\Utility\Lang\Exception;


/**
 * Class VectorException
 *
 * @package Vection\Component\Utility\Lang\Exception
 */
class VectorException extends \Exception
{
    /**
     * @throws VectorException
     */
    public static function isImmutable()
    {
        throw new self("Set is forbidden, object is immutable");
    }
}
