<?php
/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) Bjoern Klemm <vection@bjoernklemm.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility\Lang\Exception;


/**
 * Class VectorException
 *
 * @package Vection\Component\Utility\Lang\Exception
 *
 * @author  Bjoern Klemm <vection@bjoernklemm.de>
 */
class VectorException extends \Exception
{
    /**
     * @throws VectorException
     */
    public static function isImmutable(): void
    {
        throw new self('Set is forbidden, object is immutable');
    }
}
