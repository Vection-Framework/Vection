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

use DateTimeZone;
use Vection\Component\Validator\Validator;

/**
 * Class Timezone
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Timezone extends Validator
{
    protected int|null    $group;
    protected string|null $countryCode;

    /**
     * @param int|null    $group
     * @param string|null $countryCode
     */
    public function __construct(int|null $group = null, string|null $countryCode = null)
    {
        $this->group = $group ?? DateTimeZone::ALL;
        $this->countryCode = $countryCode;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['group' => $this->group, 'countryCode' => $this->countryCode];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid timezone.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return in_array($value, DateTimeZone::listIdentifiers($this->group, $this->countryCode), true);
    }
}
