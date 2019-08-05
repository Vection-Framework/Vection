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
 * Class Boolean
 *
 * @package Vection\Component\Validator\Validator
 */
class Boolean extends Validator
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
        return 'Value "{value}" is not boolean.';
    }

    /**
     * @inheritDoc
     */
    public function onValidate($value): bool
    {
        return is_bool($value);
    }
}