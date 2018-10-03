<?php

/**
 * This file is part of the Vection project.
 * Visit project at https://www.vection.de
 *
 * (c) Vection <project@vection.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Utility;

/**
 * Class Tokenizer
 *
 * @package Vection\Component\Utility
 */
class TokenGenerator
{

    /**
     * @param int $length
     *
     * @return string
     * @throws \Exception
     */
    public static function create(int $length): string
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $token = '';

        for ( $i = 0; $i < $length; $i++ ) {
            $chars = \str_shuffle($chars);
            $token .= $chars{\random_int(0, \strlen($chars) - 1)};
        }

        return $token;
    }

}
