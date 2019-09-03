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

namespace Vection\Component\Utility\Lang;


use Vection\Component\Utility\Lang\Exception\VectorException;

/**
 * Class VectorImmutable
 *
 * @package Vection\Component\Utility\Lang
 */
class VectorImmutable extends Vector
{
    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws VectorException
     */
    public function offsetSet($offset, $value)
    {
        VectorException::isImmutable();
    }

    /**
     * @param mixed $offset
     *
     * @throws VectorException
     */
    public function offsetUnset($offset)
    {
        VectorException::isImmutable();
    }
}