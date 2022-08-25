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

namespace Vection\Component\Common\IO;

use function array_pad;
use function array_shift;
use function count;
use function explode;
use function implode;
use function is_dir;
use function rtrim;
use function str_replace;

/**
 * Class FSHelper
 *
 * @package Vection\Component\Utility
 *
 * @author  Bjoern Klemm <vection@bjoernklemm.de>
 * @author  David M. Lung <vection@davidlung.de>
 */
class FSHelper
{

    /**
     * Returns the a relative path between to absolute paths.
     *
     * @param string $from Absolute path (source)
     * @param string $to   Absolute path (target)
     *
     * @return string
     */
    public static function getRelativePath(string $from, string $to): string
    {
        $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
        $to   = is_dir($to) ? rtrim($to, '\/') . '/' : $to;

        $from = str_replace('\\', '/', $from);
        $to   = str_replace('\\', '/', $to);

        $_from = explode('/', $from);
        $_to   = explode('/', $to);

        $relPath = $_to;

        foreach ( $_from as $depth => $dir ) {
            # find first non-matching dir
            if ( $dir === $to[$depth] ) {
                # ignore this directory
                array_shift($relPath);
            } else {
                # get number of remaining dirs to $from
                $remaining = (count($_from) - $depth);
                if ( $remaining > 1 ) {
                    # add traversals up to first matching dir
                    $padLength = (( count($relPath) + $remaining - 1 ) * -1);
                    $relPath   = array_pad($relPath, $padLength, '..');
                    break;
                }
                $relPath[0] = './' . $relPath[0];
            }
        }

        return implode('/', $relPath);
    }

    /**
     * @param string      $path
     * @param array       $fileparts last array entry must be the filename
     * @param string|null $ext
     *
     * <example>
     *    $fileparts = ['data','list','myDatalist'];
     *    $path = '/home/user';
     *    $filepath = FsHelper::buildFilePath($path,$fileparts,'json');
     *    echo $filepath; // /home/user/data/list/myDatalist.json
     * </example>
     *
     * @return string
     */
    public static function buildFilePath(string $path, array $fileparts, string $ext = null): string
    {
        $ext = $ext ? '.' . $ext : '';

        $path = (str_ends_with($path, '/')) ? $path : $path . DIRECTORY_SEPARATOR;

        return $path . implode(DIRECTORY_SEPARATOR, $fileparts) . $ext;
    }


    /**
     * Search in file for a string
     *
     * @param string $file
     * @param string $search
     *
     * @return bool
     */
    public static function matchInFile(string $file, string $search): bool
    {
        if ( ! is_file($file) ) {
            return false;
        }

        $content = file_get_contents($file);

        return str_contains($content, $search);
    }
}
