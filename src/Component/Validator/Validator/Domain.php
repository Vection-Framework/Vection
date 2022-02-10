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
 * Class Domain
 *
 * @package Vection\Component\Validator\Validator
 * @author  BloodhunterD <bloodhunterd@bloodhunterd.com>
 */
class Domain extends Validator
{
    protected bool $hostname;

    /**
     * @param bool $hostname
     */
    public function __construct(bool $hostname = false)
    {
        $this->hostname = $hostname;
    }

    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return ['hostname' => $this->hostname];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid domain.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return (bool) filter_var(
            $value, FILTER_VALIDATE_DOMAIN | $this->hostname ? FILTER_FLAG_HOSTNAME : FILTER_FLAG_NONE
        );
    }
}
