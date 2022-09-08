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
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class Color
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Color extends Validator
{
    public const HEX  = 1;
    public const HSL  = 2;
    public const HSLA = 4;
    public const RGB  = 8;
    public const RGBA = 16;

    protected int $format;

    /**
     * @var string[]
     */
    protected array $patterns = [
        self::HEX  => '#[0-9a-fA-F]{2}[0-9a-fA-F]{2}[0-9a-fA-F]{2}',
        self::HSL  => 'hsl\([0-9]{1,3},[0-9]{1,3}%,[0-9]{1,3}%\)',
        self::HSLA => 'hsla\([0-9]{1,3},[0-9]{1,3}%,[0-9]{1,3}%,[0-9]{0,1}\.{0,1}[0-9]{1}\)',
        self::RGB  => 'rgb\([0-2]{0,1}[0-9]{1,2},[0-2]{0,1}[0-9]{1,2},[0-2]{0,1}[0-9]{1,2}\)',
        self::RGBA => 'rgba\([0-2]{0,1}[0-9]{1,2},[0-2]{0,1}[0-9]{1,2},[0-2]{0,1}[0-9]{1,2},[0-9]{0,1}\.{0,1}[0-9]{1}\)'
    ];

    /**
     * @param int $format
     */
    public function __construct(int $format = self::HEX)
    {
        $this->format = $format;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['format' => $this->format];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" has not a valid color format.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        if (!is_string($value)) {
            throw new IllegalTypeException(
                sprintf('The value must be of type "string", but type "%s" was passed.', gettype($value))
            );
        }

        $patterns = [];
        if ($this->format & self::HEX) {
            $patterns[] = $this->patterns[self::HEX];
        }
        if ($this->format & self::HSL) {
            $patterns[] = $this->patterns[self::HSL];
        }
        if ($this->format & self::HSLA) {
            $patterns[] = $this->patterns[self::HSLA];
        }
        if ($this->format & self::RGB) {
            $patterns[] = $this->patterns[self::RGB];
        }
        if ($this->format & self::RGBA) {
            $patterns[] = $this->patterns[self::RGBA];
        }

        if (!$patterns) {
            throw new InvalidArgumentException(
                'Invalid color format. Supported formats are HEX, HSL, HSLA, RGB and RGBA.'
            );
        }

        $pattern = implode('|', $patterns);

        return preg_match("/^$pattern$/", str_replace(' ', '', $value)) === 1;
    }
}
