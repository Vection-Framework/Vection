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

use Vection\Component\Validator\Validator;
use Vection\Component\Validator\Validator\Exception\IllegalTypeException;

/**
 * Class IconName
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class IconName extends Validator
{
    public const FONT_AWESOME         = 'fa-';
    public const MATERIAL_DESIGN_ICON = 'mdi-';

    protected string $prefix;
    protected int    $maxLength;

    /**
     * @param string $prefix
     * @param int    $maxLength
     */
    public function __construct(string $prefix, int $maxLength)
    {
        $this->prefix    = $prefix;
        $this->maxLength = $maxLength;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['prefix' => $this->prefix, 'maxLength' => $this->maxLength];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid icon name.';
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

        $pattern = "/^$this->prefix[a-z0-9-]{1,$this->maxLength}$/";

        return preg_match($pattern, $value) === 1;
    }
}
