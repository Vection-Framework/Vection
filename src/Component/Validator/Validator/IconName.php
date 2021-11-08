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
     * @param string|null $prefix
     * @param int|null    $maxLength
     */
    public function __construct(string $prefix = null, int $maxLength = null)
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
        $pattern = "/^$this->prefix[a-z0-9-]{1,$this->maxLength}$/";

        return preg_match($pattern, $value) === 1;
    }
}
