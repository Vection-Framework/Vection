<?php declare(strict_types=1);

/**
 * This file is part of the AppsDock project.
 *  Visit project at https://github.com/Vection-Framework/Vection
 *
 *  (c) David Lung <vection@davidlung.de>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class Phone
 *
 * @package Vection\Component\Validator\Validator
 */
class Phone extends Validator
{
    /**
     * @inheritDoc
     */
    public function getConstraints(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): string
    {
        return 'Value "{value}" is not a valid phone number.';
    }

    /**
     * @inheritDoc
     */
    public function onValidate($value): bool
    {
        return preg_match('/^\+?[1-9]\d{1,14}$/', $value);
    }
}