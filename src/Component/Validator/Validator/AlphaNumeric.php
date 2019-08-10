<?php declare(strict_types=1);

/**
 * This file is part of the Vection-Framework project.
 * Visit project at https://github.com/Vection-Framework/Vection
 *
 * (c) David M. Lung <vection@davidlung.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Vection\Component\Validator\Validator;

use Vection\Component\Validator\Validator;

/**
 * Class AlphaNumeric
 *
 * @package Vection\Component\Validator\Validator
 */
class AlphaNumeric extends Validator
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
        return 'Value "{value}" is not alphanumeric.';
    }

    /**
     * @inheritDoc
     */
    protected function onValidate($value): bool
    {
        return (bool) preg_match('/^([a-zA-Z]{1}[a-zA-Z0-9]*)$/', $value);
    }
}