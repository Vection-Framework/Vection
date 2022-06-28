<?php

declare(strict_types=1);

namespace Vection\Component\Common;

use JsonException;
use Vection\Component\Common\Exception\RuntimeException;

/**
 * Class Json
 *
 * @package Vection\Component\Common
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Json
{
    /**
     * @param array $data
     *
     * @return VArray
     */
    public static function load(array $data): VArray
    {
        return new VArray($data);
    }

    /**
     * @param string $json
     *
     * @return VArray
     */
    public static function parse(string $json): VArray
    {
        return VArray::parseJSON($json);
    }

    /**
     * @param string $filePath
     *
     * @return VArray
     */
    public static function read(string $filePath): VArray
    {
        return VArray::parseFile($filePath);
    }

    /**
     * @param VArray $array
     * @param bool   $pretty
     * @param int    $flags
     * @param int    $depth
     *
     * @return string
     */
    public static function toString(VArray $array, bool $pretty = true, int $flags = 0, int $depth = 512): string
    {
        if ($pretty) {
            $flags += JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS;
        }

        try {
            return json_encode($array, $flags | JSON_THROW_ON_ERROR, $depth);
        }
        catch (JsonException $e) {
            throw new RuntimeException('Failed to encode Array to JSON: ' . $e->getMessage());
        }
    }

    /**
     * @param string $filePath
     * @param VArray $array
     * @param bool   $pretty
     * @param int    $flags
     * @param int    $depth
     *
     * @return void
     */
    public static function toFile(
        string $filePath,
        VArray $array,
        bool   $pretty = true,
        int    $flags  = 0,
        int    $depth  = 512
    ): void
    {
        if (!file_put_contents($filePath, self::toString($array, $pretty, $flags, $depth))) {
            throw new RuntimeException('Failed to save JSON data in file.');
        }
    }
}
