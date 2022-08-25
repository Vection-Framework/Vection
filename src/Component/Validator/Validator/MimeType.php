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
 * Class TypedArray
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class MimeType extends Validator
{
    protected bool $includeWildcard;

    /**
     * @param bool $includeWildcard
     */
    public function __construct(bool $includeWildcard = false)
    {
        $this->includeWildcard = $includeWildcard;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['includeWildcard' => $this->includeWildcard];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid mime type.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        $wildcard = $this->includeWildcard ? '|\*' : '';

        $pattern = "([a-z0-9][a-z0-9!#$&-^_.+*]{1,127}$wildcard)";

        return preg_match("/$pattern\/$pattern/i", $value) === 1;
    }
}
