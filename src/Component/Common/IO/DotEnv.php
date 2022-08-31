<?php

/**
 * This file is part of the Vection package.
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Vection\Component\Common\IO;

use RuntimeException;

/**
 * Class DotEnv
 *
 * @package Vection\Component\Utility\IO
 * @author  David M. Lung <vection@davidlung.de>
 */
class DotEnv
{
    /**
     * Parse a .env file and return the env vars as assoc array.
     * The name of the var is the array key and the env var is the array value.
     *
     * If the path is a directory, it will try to find an .env file in the given path, otherwise
     * it will parse the given file path as .env var.
     *
     * @param string $path The path where to find the .env or the file path of the .env.
     *
     * @return string[]
     */
    public static function parse(string $path): array
    {
        if (! file_exists($path)) {
            throw new RuntimeException('File not found: '.$path);
        }

        if (is_dir($path)) {
            $path = rtrim($path, '/').'/.env';
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            throw new RuntimeException('Unable to read env file: '.$path);
        }

        $envVars = [];

        foreach ($lines as $line) {
            if (strpos($line, '=')) {
                $parts = explode('=', $line);
                $envVars[trim(array_shift($parts))] = trim(implode('=', $parts));
            }
        }

        return $envVars;
    }

    /**
     * Parse the given .env file and apply the vars to $_SERVER and $_ENV.
     *
     * @param string $path
     */
    public static function apply(string $path): void
    {
        foreach (self::parse($path) as $name => $value) {
            $_SERVER[$name] = $_ENV[$name] = $value;
        }
    }
}
