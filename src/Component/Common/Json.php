<?php

declare(strict_types=1);

namespace Vection\Component\Common;

use JsonException;
use JsonSerializable;
use Vection\Component\Common\Exception\RuntimeException;

/**
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Json
{
    public static function parse(string $json, bool $immutable = false): VArray
    {
        try {
            return new VArray(json_decode($json, true, 512, JSON_THROW_ON_ERROR), $immutable);
        }
        catch (JsonException $e) {
            throw new RuntimeException('Malformed JSON: '.$e->getMessage());
        }
    }

    public static function read(string $filePath): VArray
    {
        return VArray::parseFile($filePath);
    }

    /**
     * @param VArray|array<mixed>|JsonSerializable $array
     */
    public static function encode(VArray|array|JsonSerializable $array, bool $pretty = true, int $flags = 0, int $depth = 512): string
    {
        if ($pretty) {
            $flags += JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_LINE_TERMINATORS;
        }

        try {
            return json_encode($array, $flags | JSON_THROW_ON_ERROR, $depth);
        }
        catch (JsonException $e) {
            throw new RuntimeException('Failed to encode Array to JSON: '.$e->getMessage());
        }
    }

    public static function toFile(
        string $filePath, VArray $array, bool $pretty = true, int $flags = 0, int $depth = 512
    ): void
    {
        if (!file_put_contents($filePath, self::encode($array, $pretty, $flags, $depth))) {
            throw new RuntimeException('Failed to save JSON data in file.');
        }
    }
}
