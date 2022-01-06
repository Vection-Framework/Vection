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
 * Class Locale
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Locale extends Validator
{
    public const HYPHEN     = '-';
    public const UNDERSCORE = '_';

    protected bool   $strict;
    protected string $separator;

    /**
     * @param bool   $strict
     * @param string $separator
     */
    public function __construct(bool $strict = true, string $separator = self::HYPHEN)
    {
        $this->strict    = $strict;
        $this->separator = $separator;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['strict' => $this->strict, 'separator' => $this->separator];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid locale.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $language = '[A-Za-z]{2,4}';
        $script   = '[A-Za-z]{4}';
        $region   = '[A-Za-z]{2}';

        if ($this->strict) {
            $language = '[a-z]{2,3}';
            $script   = '[A-Z]{1}[a-z]{3}';
            $region   = '[A-Z]{2}';
        }

        $pattern = sprintf(
            '/^%s([%s]%s)?([%s](%s|[\d]{3}))$/',
            $language,
            $this->separator,
            $script,
            $this->separator,
            $region
        );

        return preg_match($pattern, $value) === 1;
    }
}
