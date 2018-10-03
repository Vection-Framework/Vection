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
 * Class FSHelper
 *
 * @package Vection\Component\Utility
 */
class FSHelper
{

    /**
     * Returns the a relative path between to absolute paths.
     *
     * @param string $from Absolute path (source)
     * @param string $to Absolute path (target)
     *
     * @return string
     */
    public static function getRelativePath(string $from, string $to): string
    {
        $from = \is_dir($from) ? \rtrim($from, '\/') . '/' : $from;
        $to = \is_dir($to) ? \rtrim($to, '\/') . '/' : $to;

        $from = \str_replace('\\', '/', $from);
        $to = \str_replace('\\', '/', $to);

        $from = \explode('/', $from);
        $to = \explode('/', $to);

        $relPath = $to;

        foreach ( $from as $depth => $dir ) {
            # find first non-matching dir
            if ( $dir === $to[$depth] ) {
                # ignore this directory
                \array_shift($relPath);
            } else {
                # get number of remaining dirs to $from
                $remaining = \count($from) - $depth;
                if ( $remaining > 1 ) {
                    # add traversals up to first matching dir
                    $padLength = ( \count($relPath) + $remaining - 1 ) * -1;
                    $relPath = \array_pad($relPath, $padLength, '..');
                    break;
                }
                $relPath[0] = './' . $relPath[0];
            }
        }

        return \implode('/', $relPath);
    }
}