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

namespace Vection\Component\Http;

/**
 * Class ResourceMode
 *
 * @package Vection\Component\Http
 *
 * @author  David M. Lung <vection@davidlung.de>
 */
class ResourceMode
{
    public const READ = [
        'r'   => 'r',
        'w+'  => 'w+',
        'r+'  => 'r+',
        'x+'  => 'x+',
        'c+'  => 'c+',
        'rb'  => 'rb',
        'w+b' => 'w+b',
        'r+b' => 'r+b',
        'x+b' => 'x+b',
        'c+b' => 'c+b',
        'rt'  => 'rt',
        'w+t' => 'w+t',
        'r+t' => 'r+t',
        'x+t' => 'x+t',
        'c+t' => 'c+t',
        'a+'  => 'a+',
    ];

    public const WRITE = [
        'w'   => 'w',
        'w+'  => 'w+',
        'rw'  => 'rw',
        'r+'  => 'r+',
        'x+'  => 'x+',
        'c+'  => 'c+',
        'wb'  => 'wb',
        'w+b' => 'w+b',
        'r+b' => 'r+b',
        'x+b' => 'x+b',
        'c+b' => 'c+b',
        'w+t' => 'w+t',
        'r+t' => 'r+t',
        'x+t' => 'x+t',
        'c+t' => 'c+t',
        'a'   => 'a',
        'a+'  => 'a+',
    ];
}
