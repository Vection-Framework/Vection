<?php

declare(strict_types=1);

namespace Vection\Component\Common;

use Vection\Component\Common\Exception\RuntimeException;

/**
 * @author BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Yaml
{
    public static function parse(string $yaml, bool $immutable = false): VArray
    {
        if (!function_exists('yaml_parse')) {
            throw new RuntimeException('Yaml::parse(yaml) requires php-yaml extension to be enabled.');
        }

        $data = yaml_parse($yaml);

        if ($data === false) {
            throw new RuntimeException('Malformed YAML');
        }

        return new VArray($data ?? [], $immutable);
    }

    public static function read(string $filePath): VArray
    {
        return VArray::parseFile($filePath);
    }

    /**
     * @param VArray|array<mixed> $data
     */
    public static function encode(VArray|array $data): string
    {
        if (!function_exists('yaml_emit')) {
            throw new RuntimeException('Yaml::encode(yaml) requires php-yaml extension to be enabled.');
        }

        return yaml_emit(!$data instanceof VArray ?: $data->jsonSerialize());
    }

    /**
     * @param VArray|array<mixed> $data
     */
    public static function toFile(string $filePath, VArray|array $data): void
    {
        if (!file_put_contents($filePath, self::encode($data))) {
            throw new RuntimeException('Failed to save YAML data in file.');
        }
    }
}
