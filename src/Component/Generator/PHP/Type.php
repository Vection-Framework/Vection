<?php

namespace Vection\Component\Generator\PHP;

/**
 * Class Type
 * @package Vection\Component\Generator\PHP
 */
class Type
{
    /** @var array */
    private static $primitives = [
        'string', 'bool', 'int', 'float', 'boolean', 'array', 'mixed', 'void'
    ];

    /**
     * @param string $type
     * @return string
     */
    public static function adjust(string $type): string
    {
        if ( ! static::isPrimitive($type) ) {
            $type = '\\' . ltrim($type, '\\');
        }

        return $type;
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isPrimitive(string $type): bool
    {
        return \in_array($type, static::$primitives, true);
    }

    /**
     * @param string $type
     * @return array
     */
    public static function getTypeInfo(string $type): array
    {
        if ( static::isPrimitive($type) ) {
            $name = $type;
            $namespace = '';
            $fullName = $name;
        } else {
            $name = substr(strrchr($type, "\\"), 1);
            $namespace = substr($type, 0, strrpos($type, '\\'));
            $fullName = $namespace . '\\' . $name;
        }

        return [
            'kind'      => static::isClass($type) ? 'class' : 'primitive',
            'name'      => $name,
            'fullName'  => $fullName,
            'namespace' => $namespace
        ];
    }

    /**
     * @param string $type
     * @return bool
     */
    public static function isClass(string $type): bool
    {
        return preg_match('/[\\\][a-zA-Z_0-9]+|[a-zA-Z_0-9]+[\\\]/', $type);
    }
}