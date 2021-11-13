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

namespace Vection\Component\Validator\Validator;

use InvalidArgumentException;
use Vection\Component\Validator\Validator;

/**
 * Class TypedArray
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class TypedArray extends Validator
{
    public const BOOL   = 1;
    public const INT    = 2;
    public const FLOAT  = 4;
    public const STRING = 8;
    public const ARRAY  = 16;
    public const OBJECT = 32;
    public const NULL   = 64;

    protected int $type;

    /**
     * @var string[]
     */
    protected array $types = [
        self::BOOL   => 'boolean',
        self::INT    => 'integer',
        self::FLOAT  => 'double',
        self::STRING => 'string',
        self::ARRAY  => 'array',
        self::OBJECT => 'object',
        self::NULL   => 'NULL'
    ];

    /**
     * @param int $type
     */
    public function __construct(int $type = self::STRING)
    {
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['type' => $this->type];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        if ($this->invalidArgumentException) {
            return $this->invalidArgumentException->getMessage();
        }
        return 'Value "{value}" does not contain only the required types.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException(
                sprintf('The argument must be of type "array", but type "%s" given.', gettype($value))
            );
        }

        $types = [];

        if ($this->type & self::BOOL) {
            $types[] = $this->types[self::BOOL];
        }
        if ($this->type & self::INT) {
            $types[] = $this->types[self::INT];
        }
        if ($this->type & self::FLOAT) {
            $types[] = $this->types[self::FLOAT];
        }
        if ($this->type & self::STRING) {
            $types[] = $this->types[self::STRING];
        }
        if ($this->type & self::ARRAY) {
            $types[] = $this->types[self::ARRAY];
        }
        if ($this->type & self::OBJECT) {
            $types[] = $this->types[self::OBJECT];
        }
        if ($this->type & self::NULL) {
            $types[] = $this->types[self::NULL];
        }

        if (!$types) {
            throw new InvalidArgumentException(
                'Invalid generic type. Supported types are BOOL, INT, FLOAT, STRING, ARRAY, OBJECT and NULL.'
            );
        }

        return array_diff(array_map('gettype', $value), $types) === [];
    }
}
