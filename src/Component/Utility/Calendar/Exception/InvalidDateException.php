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

namespace Vection\Component\Utility\Calendar\Exception;


/**
 * Class InvalidDateException
 *
 * @package Vection\Component\Utility\Calendar\Exception
 */
class InvalidDateException extends \InvalidArgumentException
{
    /**
     * @param \DateTimeInterface|bool $date
     */
    public static function isValid($date)
    {
        if (false === $date){
            throw new self("Could not create date object from string");
        }
    }
}