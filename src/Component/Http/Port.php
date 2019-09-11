<?php

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *  
 * (c) David M. Lung <vection@davidlung.de>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Http;

/**
 * Class Port
 *
 * @package Vection\Component\Http
 */
class Port
{
    # https://gist.github.com/mahmoud/2fe281a8daaff26cfe9c15d2c5bf5c8b
    const DEFAULT_SCHEME_PORTS = [
        'acap' => 674, 
        'afp' => 548, 
        'dict' => 2628, 
        'dns' => 53, 
        'ftp' => 21,
        'git' => 9418, 
        'gopher' => 70, 
        'http' => 80, 
        'https' => 443, 
        'imap' => 143, 
        'ipp' => 631, 
        'ipps' => 631, 
        'irc' => 194, 
        'ircs' => 6697, 
        'ldap' => 389, 
        'ldaps' => 636, 
        'mms' => 1755, 
        'msrp' => 2855, 
        'mtqp' => 1038,
        'nfs' => 111, 
        'nntp' => 119, 
        'nntps' => 563, 
        'pop' => 110, 
        'prospero' => 1525, 
        'redis' => 6379, 
        'rsync' => 873, 
        'rtsp' => 554, 
        'rtsps' => 322, 
        'rtspu' => 5005, 
        'sftp' => 22, 
        'smb' => 445, 
        'snmp' => 161, 
        'ssh' => 22, 
        'svn' => 3690,
        'telnet' => 23, 
        'ventrilo' => 3784, 
        'vnc' => 5900, 
        'wais' => 210, 
        'ws' => 80, 
        'wss' => 443, 
    ];

    /**
     * @param string $scheme
     * @param int    $port
     *
     * @return bool
     */
    public static function isDefaultPort(string $scheme, int $port): bool
    {
        return (self::DEFAULT_SCHEME_PORTS[$scheme] ?? null) === $port;
    }

    /**
     * @param string $scheme
     *
     * @return int|null
     */
    public static function getDefaultPort(string $scheme): ? int
    {
        return self::DEFAULT_SCHEME_PORTS[$scheme] ?? null;
    }
}